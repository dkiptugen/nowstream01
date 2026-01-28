import * as $ from "jquery";
import "bootstrap"; // Ensure Bootstrap's JS is included
import "jquery-ui/dist/jquery-ui"; // Include jQuery UI for drag-and-drop

var MagicEmbedButton = function (context)
	{
		var ui = $.summernote.ui;
		
		/**
		 * Normalizes x.com URLs to twitter.com URLs.
		 * @param {string} url The URL to normalize.
		 * @returns {string} The normalized URL.
		 */
		function normalizeTwitterUrl (url)
			{
				if(url.includes('x.com'))
					{
						return url.replace('x.com', 'twitter.com');
					}
				return url;
			}
		
		/**
		 * Detects the content type from the URL and generates the appropriate embed HTML.
		 * @param {string} url The URL to embed.
		 * @returns {string} The HTML string for the embed, or an empty string if not supported.
		 */
		function getEmbedHtml (url)
			{
				let embedHtml = '';
				let videoId;
				let embedUrl;
				let postId;
				let reelId;
				let mapId;
				
				// Normalize x.com URLs upfront
				const normalizedUrl = normalizeTwitterUrl(url);
				
				// YouTube
				if(normalizedUrl.includes('youtube.com') || normalizedUrl.includes('youtu.be'))
					{
						videoId = normalizedUrl.match(/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
						if(videoId && videoId[1])
							{
								embedHtml = `<div class="embed-responsive embed-responsive-16by9 embed-container" contenteditable="false">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/${videoId[1]}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen=""></iframe>
                    <span class="remove-embed">×</span>
                </div>`;
							}
						else
							{
								console.error('Magic Embed: Invalid YouTube URL:', normalizedUrl);
							}
					}
				// Twitter (using Twitter Publish iframe embed)
				else if(normalizedUrl.includes('twitter.com'))
					{
						embedHtml = `<div class="embed-container" contenteditable="false">
                <blockquote class="twitter-tweet"><a href="${normalizedUrl}"></a></blockquote>
                <span class="remove-embed">×</span>
            </div>`;
						// Dynamically inject the Twitter script if not already present
						if(!$('script[src="https://platform.twitter.com/widgets.js"]').length)
							{
								setTimeout(function ()
								           {
									           var script = document.createElement('script');
									           script.src = 'https://platform.twitter.com/widgets.js';
									           script.async = true;
									           script.charset = 'utf-8';
									           document.body.appendChild(script);
								           }, 100);
							}
					}
				// Facebook (using Facebook iframe embed)
				else if(normalizedUrl.includes('facebook.com'))
					{
						embedHtml = `<div class="embed-responsive embed-responsive-4by3 embed-container" contenteditable="false">
                <iframe class="embed-responsive-item" src="https://www.facebook.com/plugins/post.php?href=${encodeURIComponent(normalizedUrl)}&width=500" width="500" height="600" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                <span class="remove-embed">×</span>
            </div>`;
					}
				// Spotify embed logic
				else if(normalizedUrl.includes('spotify.com'))
					{
						// Convert common Spotify share URLs to embed URLs
						embedUrl = normalizedUrl.replace('open.spotify.com/', 'https://embed.spotify.com/');
						if(!embedUrl.includes('embed.spotify.com'))
							{ // Ensure it's a valid embed URL
								console.error('Magic Embed: Invalid Spotify URL for embedding:', normalizedUrl);
								return '';
							}
						embedHtml = `<div class="embed-container" contenteditable="false">
                <iframe src="${embedUrl}" width="300" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                <span class="remove-embed">×</span>
            </div>`;
					}
				// TikTok (using TikTok iframe embed)
				else if(normalizedUrl.includes('tiktok.com'))
					{
						videoId = normalizedUrl.split('/').pop();
						if(videoId)
							{
								embedHtml = `<div class="embed-container" contenteditable="false">
                    <iframe src="https://www.tiktok.com/embed/${videoId}" width="325" height="575" frameborder="0" allowfullscreen></iframe>
                    <span class="remove-embed">×</span>
                </div>`;
							}
						else
							{
								console.error('Magic Embed: Invalid TikTok URL:', normalizedUrl);
							}
					}
				// Instagram embed logic
				else if(normalizedUrl.includes('instagram.com'))
					{
						if(normalizedUrl.includes('/p/'))
							{
								postId = normalizedUrl.split('/p/')[1].split('/')[0]; // Extract the post ID
								embedHtml = `<div class="embed-container" contenteditable="false">
                                <iframe src="https://www.instagram.com/p/${postId}/embed" width="400" height="480" frameborder="0" scrolling="no" allowtransparency="true"></iframe>
                                <span class="remove-embed">×</span>
                            </div>`;
							}
						else if(normalizedUrl.includes('/reel/'))
							{
								reelId = normalizedUrl.split('/reel/')[1].split('/')[0]; // Extract the reel ID
								embedHtml = `<div class="embed-container" contenteditable="false">
                                <iframe src="https://www.instagram.com/reel/${reelId}/embed" width="400" height="480" frameborder="0" scrolling="no" allowtransparency="true"></iframe>
                                <span class="remove-embed">×</span>
                            </div>`;
							}
						else
							{
								console.error('Magic Embed: Invalid Instagram URL. Expected format: https://www.instagram.com/p/{post_id}/ or https://www.instagram.com/reel/{reel_id}/');
							}
					}
				// Google Maps embed logic (for both regular URLs and app URLs)
				else if(normalizedUrl.includes('maps.google.com') || normalizedUrl.includes('google.com/maps'))
					{
						if(normalizedUrl.includes('/maps/embed'))
							{ // Already an embed URL
								embedHtml = `<div class="embed-responsive embed-responsive-4by3 embed-container" contenteditable="false">
                    <iframe class="embed-responsive-item" src="${normalizedUrl}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    <span class="remove-embed">×</span>
                </div>`;
							}
						else if(normalizedUrl.includes('/maps/') && normalizedUrl.includes('!3d'))
							{ // Regular Google Maps URL with coordinates
								// Attempt to convert regular map URLs to embed URLs
								// This is a simplified conversion, full conversion might be more complex for all map
								// types
								embedUrl = normalizedUrl.replace('/maps/', '/maps/embed?pb=');
								if(embedUrl.includes('!3d'))
									{ // Basic check if it looks like a place URL
										embedHtml = `<div class="embed-responsive embed-responsive-4by3 embed-container" contenteditable="false">
                        <iframe class="embed-responsive-item" src="${embedUrl}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        <span class="remove-embed">×</span>
                    </div>`;
									}
								else
									{
										console.error('Magic Embed: Could not convert Google Maps URL to embed format:', normalizedUrl);
									}
							}
						else if(normalizedUrl.includes('goo.gl/maps/'))
							{ // goo.gl shortened map URLs
								// This often redirects to a full maps.google.com URL, so direct embedding is tricky.
								// It might be better to instruct the user to get the "embed map" code from Google Maps.
								console.warn('Magic Embed: Shortened Google Maps URLs (goo.gl/maps/) are not directly embeddable. Please use the "Embed Map" option from Google Maps for the URL.');
							}
						else if(normalizedUrl.includes('/place/'))
							{
								// For direct "place" URLs, construct a basic embed link.
								// This might not capture all details like directions.
								embedUrl = `https://www.google.com/maps/embed?q=${encodeURIComponent(normalizedUrl)}`;
								embedHtml = `<div class="embed-responsive embed-responsive-4by3 embed-container" contenteditable="false">
                    <iframe class="embed-responsive-item" src="${embedUrl}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    <span class="remove-embed">×</span>
                </div>`;
							}
						else
							{
								console.error('Magic Embed: Invalid Google Maps URL. Please use a direct embed URL or a standard map URL:', normalizedUrl);
							}
					}
				else
					{
						console.warn('Magic Embed: Unsupported URL type:', normalizedUrl);
					}
				
				return embedHtml;
			}
		
		/**
		 * Inserts the embed content into the Summernote editor.
		 * @param {string} url The URL to embed.
		 * @param {object} range The current Summernote range object.
		 */
		function insertAndConfigureEmbed (url, range)
			{
				const embedHtml = getEmbedHtml(url);
				
				if(embedHtml)
					{
						// Wrap the embed in a contenteditable="false" div to make it a single unit
						const $node = $('<div>').html(embedHtml);
						range.insertNode($node.get(0));
						context.invoke('editor.focus');
						
						const $embedContainer = $node.find('.embed-container');
						
						// Make the embed container draggable
						$embedContainer.draggable({
							                          containment: 'parent',
							                          cursor     : 'move',
							                          handle     : 'iframe', // Allow dragging by clicking on the iframe
						                          });
						
						// Add remove functionality
						$embedContainer.find('.remove-embed').on('click', function ()
						{
							$(this).closest('.embed-container').remove();
						});
						
						// Add a border on hover for better visual feedback
						$embedContainer.hover(
							function () { $(this).css('border', '1px solid #ccc'); },
							function () { $(this).css('border', 'none'); }
						);
						
					}
				else
					{
						alert('Could not embed the provided URL. Please check the URL and try again.');
					}
			}
		
		// Create the Magic Embed button
		var button = ui.button({
			                       contents: '<i class="fas fa-magic"></i> Magic Embed',
			                       tooltip : 'Embed YouTube, Twitter/X, Facebook, Spotify, TikTok, Instagram, or Maps',
			                       click   : function ()
				                       {
					                       var range = context.invoke('editor.createRange'); // Get the current caret
					                                                                         // position
					                       var selectedText = range.toString().trim(); // Get the selected text or URL
					                       
					                       if(selectedText)
						                       {
							                       insertAndConfigureEmbed(selectedText, range);
						                       }
					                       else
						                       {
							                       // Prompt the user to enter a URL
							                       var url = prompt('Enter a URL from YouTube, Twitter/X, Facebook, Spotify, TikTok, Instagram, or Google Maps:');
							                       if(url)
								                       {
									                       insertAndConfigureEmbed(url.trim(), range);
								                       }
						                       }
				                       }
		                       });
		
		return button.render();
	};

// Extend jQuery fn to allow direct invocation (less common for Summernote plugins)
$.fn.extend({
	            insertMagicEmbed: function (context)
		            {
			            return MagicEmbedButton(context);
		            }
            });

// Register the plugin with Summernote
$.extend($.summernote.plugins, {
	'magicEmbed': function (context)
		{
			context.memo('button.magicEmbed', function ()
			{
				return MagicEmbedButton(context);
			});
		}
});