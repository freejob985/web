<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupportChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supportChannels = SupportChannel::ordered()->get();
        return view('admin.support-channels.index', compact('supportChannels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.support-channels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'nullable|string',
            'contact_info' => 'required|string|max:255',
            'availability' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $request->sort_order ?? 0;

        SupportChannel::create($data);

        return redirect()->route('admin.support-channels.index')
            ->with('success', 'تم إنشاء قناة الدعم بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(SupportChannel $supportChannel)
    {
        return view('admin.support-channels.show', compact('supportChannel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupportChannel $supportChannel)
    {
        return view('admin.support-channels.edit', compact('supportChannel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupportChannel $supportChannel)
    {
        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'nullable|string',
            'contact_info' => 'required|string|max:255',
            'availability' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $request->sort_order ?? 0;

        $supportChannel->update($data);

        return redirect()->route('admin.support-channels.index')
            ->with('success', 'تم تحديث قناة الدعم بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupportChannel $supportChannel)
    {
        $supportChannel->delete();

        return redirect()->route('admin.support-channels.index')
            ->with('success', 'تم حذف قناة الدعم بنجاح');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(SupportChannel $supportChannel)
    {
        $supportChannel->update(['is_active' => !$supportChannel->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $supportChannel->is_active,
            'message' => $supportChannel->is_active ? 'تم تفعيل القناة' : 'تم إلغاء تفعيل القناة'
        ]);
    }
}
