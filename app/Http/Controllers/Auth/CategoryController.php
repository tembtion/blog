<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Model;

class CategoryController extends Controller
{
    /**
     * 分类列表
     */
    public function index(Request $request)
    {
        $result = array();
        $result['category'] = Model\TermTaxonomy::where('taxonomy', 'category')->with('term')->paginate(10);

        return view('auth.category.index', $result);
    }

    /**
     * 分类添加
     */
    public function add(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ], [
                'name.required' => '分类名称不能为空',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            // 判断该分类是否存在
            $isExist = Model\Term::where('name', $request->input('name'))->first();
            if ($isExist) {
                throw new \Exception('该分类已经存在');
            }

            $term = new Model\Term();
            $term->name = $request->input('name');
            $term->save();

            $termTaxonomy = new Model\TermTaxonomy();
            $termTaxonomy->taxonomy = 'category';
            $term->termTaxonomy()->save($termTaxonomy);

            $result['id'] = $term->term_id;
            $result['name'] = $term->name;
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 分类编辑
     */
    public function edit(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'term_id' => 'required',
                'name' => 'required',
            ], [
                'term_id.required' => '分类ID不能为空',
                'name.required' => '分类名称不能为空',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $model = Model\Term::where('term_id', $request->input('term_id'));
            $model->update(['name' => $request->input('name')]);
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 分类删除
     */
    public function delete(Request $request)
    {
        $result = array('result' => true);
        try {
            $term = Model\Term::whereIn('term_id', $request->input('ids'));
            $term->delete();

            $termTaxonomy = Model\TermTaxonomy::whereIn('term_id', $request->input('ids'));
            $termTaxonomy->delete();
        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
