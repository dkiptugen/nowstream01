<?php

	namespace App\Http\Controllers\Backend;

	use App\Http\Controllers\Controller;
	use App\Http\Datatables\ChannelStreamDatatable;
	use App\Models\Channel;
    use App\Traits\Meta;
    use Illuminate\Http\Request;

	class ContentController extends Controller
		{
            use Meta;
            public $data = [];
            public function __construct()
                {
                    $this->data = self::product_def();
                }


		}
