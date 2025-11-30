<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AboutPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = AboutPage::getSections();
        $aboutPages = AboutPage::ordered()->get()->groupBy('section');
        
        return view('admin.about-pages.index', compact('sections', 'aboutPages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = AboutPage::getSections();
        return view('admin.about-pages.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section' => 'required|string|in:' . implode(',', array_keys(AboutPage::getSections())),
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'extra_data' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('about-pages', 'public');
            $data['image'] = $imagePath;
        }

        // Handle extra_data - Laravel will automatically cast to JSON
        if ($request->has('extra_data')) {
            $data['extra_data'] = $request->extra_data;
        }

        AboutPage::create($data);

        return redirect()->route('admin.about-pages.index')
            ->with('success', 'تم إنشاء المحتوى بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(AboutPage $aboutPage)
    {
        return view('admin.about-pages.show', compact('aboutPage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AboutPage $aboutPage)
    {
        $sections = AboutPage::getSections();
        return view('admin.about-pages.edit', compact('aboutPage', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AboutPage $aboutPage)
    {
        $validator = Validator::make($request->all(), [
            'section' => 'required|string|in:' . implode(',', array_keys(AboutPage::getSections())),
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'extra_data' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($aboutPage->image) {
                Storage::disk('public')->delete($aboutPage->image);
            }
            
            $imagePath = $request->file('image')->store('about-pages', 'public');
            $data['image'] = $imagePath;
        }

        // Handle extra_data - Laravel will automatically cast to JSON
        if ($request->has('extra_data')) {
            $data['extra_data'] = $request->extra_data;
        }

        $aboutPage->update($data);

        return redirect()->route('admin.about-pages.index')
            ->with('success', 'تم تحديث المحتوى بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AboutPage $aboutPage)
    {
        // Delete image if exists
        if ($aboutPage->image) {
            Storage::disk('public')->delete($aboutPage->image);
        }

        $aboutPage->delete();

        return redirect()->route('admin.about-pages.index')
            ->with('success', 'تم حذف المحتوى بنجاح');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(AboutPage $aboutPage)
    {
        $aboutPage->update(['is_active' => !$aboutPage->is_active]);
        
        $status = $aboutPage->is_active ? 'مفعل' : 'معطل';
        return redirect()->back()
            ->with('success', "تم $status المحتوى بنجاح");
    }

    /**
     * Show content by section
     */
    public function showSection($section)
    {
        $sections = AboutPage::getSections();
        $sectionName = $sections[$section] ?? $section;
        $aboutPages = AboutPage::section($section)->ordered()->get();
        
        return view('admin.about-pages.section', compact('section', 'sectionName', 'aboutPages'));
    }

    /**
     * Get content by section for API
     */
    public function getBySection($section)
    {
        $content = AboutPage::section($section)->active()->ordered()->get();
        return response()->json($content);
    }
}
