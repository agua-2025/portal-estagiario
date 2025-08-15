<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicDocumentController extends Controller
{
    public function index()
    {
        $docs = PublicDocument::latest('published_at')->paginate(15);
        return view('admin.public-docs.index', compact('docs'));
    }

    public function create()
    {
        return view('admin.public-docs.form', ['doc' => new PublicDocument()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => ['required','string','max:255'],
            'type'         => ['nullable','in:edital,manual,cronograma'],
            'file'         => ['required','file','mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip','max:20480'], // 20MB
            'published_at' => ['required','date'],
            'is_published' => ['sometimes','boolean'],
        ]);

        $path = $request->file('file')->store('public/docs'); // storage/app/public/docs
        $size = Storage::size($path);

        PublicDocument::create([
            'title'        => $data['title'],
            'type'         => $data['type'] ?? null,
            'file_path'    => $path,
            'file_size'    => $size,
            'published_at' => $data['published_at'],
            'is_published' => (bool)($data['is_published'] ?? true),
        ]);

        return redirect()->route('admin.public-docs.index')->with('success','Documento criado com sucesso.');
    }

    public function edit(PublicDocument $public_doc)
    {
        return view('admin.public-docs.form', ['doc' => $public_doc]);
    }

    public function update(Request $request, PublicDocument $public_doc)
    {
        $data = $request->validate([
            'title'        => ['required','string','max:255'],
            'type'         => ['nullable','in:edital,manual,cronograma'],
            'file'         => ['nullable','file','mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip','max:20480'],
            'published_at' => ['required','date'],
            'is_published' => ['sometimes','boolean'],
        ]);

        if ($request->hasFile('file')) {
            if ($public_doc->file_path && Storage::exists($public_doc->file_path)) {
                Storage::delete($public_doc->file_path);
            }
            $public_doc->file_path = $request->file('file')->store('public/docs');
            $public_doc->file_size = Storage::size($public_doc->file_path);
        }

        $public_doc->fill([
            'title'        => $data['title'],
            'type'         => $data['type'] ?? null,
            'published_at' => $data['published_at'],
            'is_published' => (bool)($data['is_published'] ?? false),
        ])->save();

        return redirect()->route('admin.public-docs.index')->with('success','Documento atualizado com sucesso.');
    }

    public function destroy(PublicDocument $public_doc)
    {
        if ($public_doc->file_path && Storage::exists($public_doc->file_path)) {
            Storage::delete($public_doc->file_path);
        }
        $public_doc->delete();

        return back()->with('success','Documento excluído.');
    }
}
