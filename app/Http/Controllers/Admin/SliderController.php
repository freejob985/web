<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::orderBy('sort_order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'background_image' => 'nullable|string|max:500',
            'badge' => 'nullable|string|max:255',
            'gradient_color' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/sliders', $imageName);
            $data['image'] = 'sliders/' . $imageName;
        }

        $data['is_active'] = $request->has('is_active');

        Slider::create($data);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'تم إنشاء السلايدر بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        return view('admin.sliders.show', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'background_image' => 'nullable|string|max:500',
            'badge' => 'nullable|string|max:255',
            'gradient_color' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($slider->image && Storage::exists('public/' . $slider->image)) {
                Storage::delete('public/' . $slider->image);
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/sliders', $imageName);
            $data['image'] = 'sliders/' . $imageName;
        }

        $data['is_active'] = $request->has('is_active');

        $slider->update($data);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'تم تحديث السلايدر بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        // Delete image if exists
        if ($slider->image && Storage::exists('public/' . $slider->image)) {
            Storage::delete('public/' . $slider->image);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')
            ->with('success', 'تم حذف السلايدر بنجاح');
    }

    /**
     * Toggle slider status
     */
    public function toggle(Slider $slider)
    {
        $slider->update(['is_active' => !$slider->is_active]);
        
        $status = $slider->is_active ? 'مفعل' : 'معطل';
        return redirect()->back()
            ->with('success', "تم $status السلايدر بنجاح");
    }
}
