<?php

    namespace App\Services;

    use Intervention\Image\ImageManager;
    use Intervention\Image\Drivers\Gd\Driver;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    /**
     * Processes an image to apply various transformations leveraging smart adaptations, such as landscape adjustments
     * and golden ratio calculations, and saves the modified image with a watermark if configured.
     */
    class ProcessImage
        {

        /**
         * Process Image to Landscape with Smart Adaptation
         */
            protected ImageManager $manager;
            public float           $phi = 1.61803398875;

            public function __construct(
                public int     $baseDimension = 1200, // The "Desired" size
                public ?string $logoPath = null
            )
                {
                    $this->manager = new ImageManager(new Driver());
                }

        /**
         * Processes an image, applies watermarking, and saves it as a WebP file.
         *
         * This method performs the following operations:
         * 1. Reads an image from the provided source and extracts EXIF data if available.
         * 2. Analyzes the dimensions of the image to determine its aspect ratio.
         *    - If the image is approximately square (within a 5% margin), it optimizes for a 1:1 orientation.
         *    - For other aspect ratios, it adjusts the dimensions following the Golden Ratio logic.
         * 3. Applies a smart watermark to the processed canvas if a valid logo path exists.
         * 4. Saves the final processed image as a WebP file to the specified disk.
         *
         * @param string $source The source path or URL of the image to be processed.
         * @param string $disk The storage disk where the processed image will be saved. Defaults to 'linode'.
         *
         * @return string The path of the saved WebP image.
         */
            public function execute($source, string $disk = 'linode')
            : string
                {
                    $img = $this->manager->read($source);

                    try
                        {
                            $img->exif();
                        }
                    catch (\Exception $e)
                        {
                        }

                    $imgWidth     = $img->width();
                    $imgHeight    = $img->height();
                    $currentRatio = $imgWidth / $imgHeight;

                    // 1. Detect if it's a square (within a 5% margin of error)
                    if (abs($currentRatio - 1.0) < 0.05)
                        {
                            // OPTIMIZED SQUARE: Maintain 1:1 orientation
                            $canvas = $this->handleSquareOptimization($img);
                        }
                    else
                        {
                            // OTHER: Fallback to Golden Ratio orientation logic
                            $isPortrait = $imgHeight > $imgWidth;
                            if ($isPortrait)
                                {
                                    $targetHeight = $this->baseDimension;
                                    $targetWidth  = (int)round($targetHeight / $this->phi);
                                }
                            else
                                {
                                    $targetWidth  = $this->baseDimension;
                                    $targetHeight = (int)round($targetWidth / $this->phi);
                                }
                            $canvas = $img->cover($targetWidth, $targetHeight, 'center');
                        }

                    // 2. Watermark
                    if ($this->logoPath && file_exists($this->logoPath))
                        {
                            $this->applySmartWatermark($canvas, $canvas->width(), $canvas->height());
                        }

                    // 3. Save as WebP
                    $filename = 'processed/' . Str::random(20) . '.webp';
                    Storage::disk($disk)->put($filename, $canvas->toWebp(90)->toString());

                    return $filename;
                }

        /**
         * Handles the optimization of square images based on their dimensions.
         *
         * This method scales down the image if its width exceeds the set base dimension.
         * If the width is less than or equal to the base dimension, the image is returned
         * unchanged to maintain its quality.
         *
         * @param mixed $img An instance of the image to be optimized.
         *
         * @return mixed The optimized image, either resized or in its original dimensions.
         */
            protected function handleSquareOptimization($img)
                {
                    // If the square is larger than our base dimension, scale it down.
                    // If it's smaller, we keep it as is to preserve quality.
                    if ($img->width() > $this->baseDimension) {
                        return $img->resize($this->baseDimension, $this->baseDimension);
                    }

                    return $img;
                }

        /**
         * Applies smart padding to an image by creating a background canvas with the appropriate color
         * and placing the scaled image onto the canvas.
         *
         * This method determines the background color (black or white) based on the brightness of a sample
         * pixel in the image's corner. The background canvas is created using the golden ratio dimensions,
         * and the image is scaled and placed in the center of the canvas.
         *
         * @param mixed $img The image instance to which smart padding should be applied.
         * @param int $targetWidth The width of the background canvas.
         * @param int $targetHeight The height of the background canvas.
         *
         * @return mixed Returns the canvas with the processed image.
         */
            protected function applySmartPadding($img, int $targetWidth, int $targetHeight)
                {
                    // 1. Sample brightness of corner to pick White or Black border
                    $color      = $img->pickColor(5, 5);
                    $brightness = ($color->red() * 0.299) + ($color->green() * 0.587) + ($color->blue() * 0.114);
                    $bgColor    = ($brightness > 128) ? '#ffffff' : '#000000';

                    // 2. Create the canvas using Golden Ratio calculated dimensions
                    $canvas = $this->manager->create($targetWidth, $targetHeight)->fill($bgColor);

                    // 3. Scale the square image to fit within the golden canvas
                    // We scale based on the shorter side to ensure it fits comfortably
                    $margin = 60;
                    $img->scale(height: $targetHeight - $margin);

                    return $canvas->place($img, 'center');
                }

        /**
         * Applies blurred sidebars to an image based on the target dimensions.
         *
         * This method creates a blurred background using the specified golden ratio dimensions,
         * scales the original image to fit the target height, and then places the scaled image
         * at the center of the blurred background.
         *
         * @param mixed $img The image object to be processed.
         * @param int $targetWidth The target width of the final image with blurred sidebars.
         * @param int $targetHeight The target height of the final image with blurred sidebars.
         *
         * @return mixed Returns the processed image with blurred sidebars applied.
         */
            protected function applyBlurredSidebars($img, int $targetWidth, int $targetHeight)
                {
                    // 1. Create the Golden Ratio landscape/portrait blurred background
                    $canvas = $img->clone();
                    $canvas->cover($targetWidth, $targetHeight)->blur(40);

                    // 2. Scale original to fit the target height exactly
                    $img->scale(height: $targetHeight);

                    return $canvas->place($img, 'center');
                }

        /**
         * Applies a smart watermark to the given canvas.
         *
         * The watermark is scaled relative to the smaller side of the
         * canvas dimensions. Auto-inversion of the watermark color
         * is performed based on the brightness of a specific picked
         * color from the canvas. The watermark is then placed at the
         * bottom-right corner of the canvas with a slight offset.
         *
         * @param mixed $canvas An instance of the canvas where the watermark will be applied.
         * @param int $w The width of the canvas.
         * @param int $h The height of the canvas.
         *
         * @return void
         */
            protected function applySmartWatermark($canvas, $w, $h)
            : void
                {
                    try
                        {
                            $watermark = $this->manager->read($this->logoPath);

                            // Scale relative to the shorter side (15% for a subtle look)
                            $baseSide = min($w, $h);
                            $watermark->scale(width: (int)($baseSide * 0.15));

                            // Detect brightness at the bottom-right corner
                            $color      = $canvas->pickColor($w - 30, $h - 30);
                            $brightness = ($color->red() * 0.299) + ($color->green() * 0.587) + ($color->blue() * 0.114);

                            // Auto-invert: if background is light, make watermark dark (and vice versa)
                            if ($brightness > 170)
                                {
                                    $watermark->invert();
                                }

                            // Add 60% opacity to make it professional and "etched"
                            $watermark->opacity(0.6);

                            $canvas->place($watermark, 'bottom-right', 20, 20);
                        }
                    catch (\Exception $e)
                        {
                            // If the logo file is unreadable, we just skip it so the image still saves
                        }
                }
        }
