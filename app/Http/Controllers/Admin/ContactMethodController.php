<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactMethodController extends Controller
{
    public function index()
    {
        $contactMethods = ContactMethod::orderBy('sort_order')->get();
        return view('admin.contact_methods.index', compact('contactMethods'));
    }

    public function create()
    {
        return view('admin.contact_methods.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'value' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        ContactMethod::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'value' => $request->value,
            'link' => $request->link,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.contact-methods.index')->with('success', 'تم إضافة طريقة التواصل بنجاح');
    }

    public function edit($id)
    {
        $contactMethod = ContactMethod::findOrFail($id);
        return view('admin.contact_methods.edit', compact('contactMethod'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'value' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $contactMethod = ContactMethod::findOrFail($id);
        $contactMethod->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'value' => $request->value,
            'link' => $request->link,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.contact-methods.index')->with('success', 'تم تحديث طريقة التواصل بنجاح');
    }

    public function destroy($id)
    {
        $contactMethod = ContactMethod::findOrFail($id);
        $contactMethod->delete();

        return redirect()->route('admin.contact-methods.index')->with('success', 'تم حذف طريقة التواصل بنجاح');
    }
}