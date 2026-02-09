<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\Util\Json;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\View\View|string
     */
        public function index()
            {
                return view('Backend.modules.category.index',$this->data);
            }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\View\View
     */
        public function create()
            {
                $this->data['cat']  =   Category::get();
                return view('Backend.modules.category.add',$this->data);
            }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array|\CodeIgniter\HTTP\RedirectResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector|void
     */
        public function store(StoreCategory $request)
            {
                $validateddata  = $request->validated();
                if($validateddata)
                    {
                        //dd($request->all());
                        $category                   =   new Category();
                        $category->name             =   $request->cat_name;
                        $category->description      =   $request->description;

                        $category->top_menu         =   $request->topmenu;
                        $category->parent_id        =   $request->p_cat;
                        $category->position         =   $request->list_order;
//                        $category->status           =   $request->status;
                        $category->user_id          =   Auth::user()->id;
                        $res                        =   $category->save();
                        if($res)
                            {
                                $keywords   =   explode(',',$request->keywords);
                                foreach ($keywords as $value)
                                    {
                                        $tag = new Tag();
                                        $tag->name =$value;
                                        $category->tags()->save($tag);
                                    }
                                return self::success('Category', 'addition success',route('category.index'));
                            }
                        return self::failed('Category', 'addition failed',route('category.index'));
                    }
                return self::failed('Category', $validateddata,route('category.index'));
            }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\View\View
     */
        public function edit($id)
            {
                $this->data['cat']      =   Category::get();
                $this->data['category'] =   Category::with('tags')->find($id);
                $this->data['keywords'] =  implode(',',$this->data['category']->tags->pluck('name')->toArray())   ;
                return view('Backend.modules.category.edit',$this->data);
            }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector|void
     */
        public function update(UpdateCategory $request, $id)
            {

                $validateddata  = $request->validated();
                if($validateddata)
                    {
                        $category                   =   Category::find($id);
                        $category->name             =   $request->cat_name;
                        $category->description      =   $request->description;
                        $category->top_menu         =   $request->topmenu??0;
                        $category->parent_id        =   $request->p_cat;
                        $category->position         =   $request->list_order;
                        //$category->status           =   $request->status;
                        $res                        =   $category->save();
                        if($res)
                            {
                                $new    =   collect(explode(',',$request->keywords));
                                $old    =   $category->tags->pluck('name');
                                $add    =   collect($new->diff($old)->all());
                                $rem    =   collect($old->diff($new)->all());
                                foreach ($add as $value)
                                    {
                                        $tag = new Tag();
                                        $tag->name =$value;
                                        $category->tags()->save($tag);
                                    }
                                foreach ($rem as $val)
                                    {
                                        $category->tags()->where(['name'=>$val])->delete();
                                    }
                                return self::success('Category', 'Update success',route('category.index'));
                            }
                        return self::failed('Category', 'Update failed',route('category.index'));
                    }
                return self::failed('Category', $validateddata,route('category.index'));
            }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return array|\Illuminate\Http\Response
     */
        public function destroy($id)
            {

                $category   =   Category::with('keyword')->find($id);
                $res = $category->delete();
                if($res)
                    {
                        $category->tags()->delete();
                        return self::success('Category', 'Delete successful',route('category.index'));
                    }
                return self::failed('Category', 'Delete failed',route('category.index'));

            }
        public function datatable(Request $request)
            {

            }
}
