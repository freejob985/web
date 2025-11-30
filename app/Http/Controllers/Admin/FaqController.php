<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')->orderBy('created_at')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_ar' => 'required|string|max:255',
            'answer_ar' => 'required|string',
            'category' => 'required|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Faq::create([
            'question_ar' => $request->question_ar,
            'answer_ar' => $request->answer_ar,
            'category' => $request->category,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'تم إضافة السؤال الشائع بنجاح');
    }

    public function show(Faq $faq)
    {
        return view('admin.faqs.show', compact('faq'));
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validator = Validator::make($request->all(), [
            'question_ar' => 'required|string|max:255',
            'answer_ar' => 'required|string',
            'category' => 'required|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $faq->update([
            'question_ar' => $request->question_ar,
            'answer_ar' => $request->answer_ar,
            'category' => $request->category,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'تم تحديث السؤال الشائع بنجاح');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')
            ->with('success', 'تم حذف السؤال الشائع بنجاح');
    }
}