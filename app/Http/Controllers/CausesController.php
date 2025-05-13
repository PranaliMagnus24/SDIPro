<?php

namespace App\Http\Controllers;
use App\Models\Causes;
use App\Models\DonationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class CausesController extends Controller
{

public function index()
    {
        $categories = DonationCategory::all();
        $causes = Causes::latest()->paginate(10);
        return view('causes.list', compact('causes', 'categories'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        $categories = DonationCategory::all();
        return view('causes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'amount' => 'required',
            'excerpt' => 'required',
            'category' => 'required',
            'deadline' => 'required',
            'metatitle' => 'nullable',
            'metadescription' => 'nullable',
            'ogmetatitle' => 'nullable',
            'ogmetadescription' => 'nullable',
            'status' => 'nullable',
            'upload_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ogmetaimage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachment' => 'required|file|mimes:pdf,doc,docx|max:5048',
        ]);
    
        $causes = new Causes();
        $causes->fill($request->except(['upload_image', 'ogmetaimage', 'attachment']));
    
        if ($request->hasFile('upload_image')) {
            $imageName = time().'_'.$request->file('upload_image')->getClientOriginalName();
            $request->file('upload_image')->move(public_path('causes/images'), $imageName);
            $causes->upload_image = 'causes/images/' . $imageName;
        }
    
        if ($request->hasFile('ogmetaimage')) {
            $ogImageName = time().'_'.$request->file('ogmetaimage')->getClientOriginalName();
            $request->file('ogmetaimage')->move(public_path('causes/images'), $ogImageName);
            $causes->ogmetaimage = 'causes/images/' . $ogImageName;
        }
    
        if ($request->hasFile('attachment')) {
            $pdfName = time().'_'.$request->file('attachment')->getClientOriginalName();
            $request->file('attachment')->move(public_path('causes/pdf'), $pdfName);
            $causes->attachment = 'causes/pdf/' . $pdfName;
        }
    
        $causes->save();
    
        return redirect()->route('causeslist')->with('success', 'Cause created successfully.');
    }
    public function edit($id)
    {
        $causes = Causes::findOrFail($id);
        $categories = DonationCategory::all();
        return view('causes.edit', compact('causes', 'categories'));
    }

    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'title' => 'required',
        'content' => 'required',
        'amount' => 'required',
        'excerpt' => 'required',
        'category' => 'required',
        'deadline' => 'required',
        'metatitle' => 'nullable',
        'metadescription' => 'nullable',
        'ogmetatitle' => 'nullable',
        'ogmetadescription' => 'nullable',
        'status' => 'nullable',
        'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'ogmetaimage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:5048',
    ]);
    
    $causes = Causes::findOrFail($id);
    $causes->fill($request->except(['upload_image', 'ogmetaimage', 'attachment']));
    
    // Upload main image
    if ($request->hasFile('upload_image')) {
        if ($causes->upload_image && file_exists(public_path($causes->upload_image))) {
            unlink(public_path($causes->upload_image));
        }
        $imageName = time().'_'.$request->file('upload_image')->getClientOriginalName();
        $request->file('upload_image')->move(public_path('causes/images'), $imageName);
        $causes->upload_image = 'causes/images/' . $imageName;
    }
    
    // Upload OG image
    if ($request->hasFile('ogmetaimage')) {
        if ($causes->ogmetaimage && file_exists(public_path($causes->ogmetaimage))) {
            unlink(public_path($causes->ogmetaimage));
        }
        $ogImageName = time().'_'.$request->file('ogmetaimage')->getClientOriginalName();
        $request->file('ogmetaimage')->move(public_path('causes/images'), $ogImageName);
        $causes->ogmetaimage = 'causes/images/' . $ogImageName;
    }
    
    // Upload attachment
    if ($request->hasFile('attachment')) {
        if ($causes->attachment && file_exists(public_path($causes->attachment))) {
            unlink(public_path($causes->attachment));
        }
        $pdfName = time().'_'.$request->file('attachment')->getClientOriginalName();
        $request->file('attachment')->move(public_path('causes/pdf'), $pdfName);
        $causes->attachment = 'causes/pdf/' . $pdfName;
    }
    
    $causes->save();
    
    return redirect()->route('causeslist')->with('success', 'Cause updated successfully.');
}    
    public function destroy($id)
    {
        $causes = Causes::findOrFail($id);
        if ($causes->upload_image) Storage::disk('public')->delete($causes->upload_image);
        if ($causes->ogmetaimage) Storage::disk('public')->delete($causes->ogmetaimage);
        if ($causes->attachment) Storage::disk('public')->delete($causes->attachment);
        $causes->delete();

        return redirect()->route('causeslist')->with('success', 'Cause deleted successfully.');
    }
    public function show($id)
{
    $causes = Causes::findOrFail($id);
   

    return view('causes.view', compact('causes'));
}

}
