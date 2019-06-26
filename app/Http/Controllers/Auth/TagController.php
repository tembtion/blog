<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Model;

class TagController extends Controller
{
    /**
     * 标签列表
     */
    public function index(Request $request)
    {
        $result = array();
        $result['tag'] = Model\TermTaxonomy::where('taxonomy', 'post_tag')->with('term')->paginate(10);

        return view('auth.tag.index', $result);
    }

    /**
     * 标签添加
     */
    public function add(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ], [
                'name.required' => '标签名称不能为空',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $data = [];
            $tags = explode(',', $request->input('name'));
            foreach ($tags as $tag) {
                $term = new Model\Term();
                $term->name = $tag;
                $term->save();

                $termTaxonomy = new Model\TermTaxonomy();
                $termTaxonomy->taxonomy = 'post_tag';
                $term->termTaxonomy()->save($termTaxonomy);
                $data[$term->term_id] = $tag;
            }
            $result['data'] = $data;

        } catch(\Exception $e) {
            $result['result'] = false;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    /**
     * 标签编辑
     */
    public function edit(Request $request)
    {
        $result = array('result' => true);
        try {
            $validator = Validator::make($request->all(), [
                'term_id' => 'required',
                'name' => 'required',
            ], [
                'term_id.required' => '标签ID不能为空',
                'name.required' => '标签名称不能为空',
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
     * 标签删除
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
