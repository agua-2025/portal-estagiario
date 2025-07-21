<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page; // Importa o modelo Page
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Importa a classe Str para gerar slugs

class PageController extends Controller
{
    /**
     * Exibe uma lista de todas as páginas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pages = Page::all(); // Obtém todas as páginas do banco de dados
        return view('admin.pages.index', compact('pages')); // Retorna a view de índice com as páginas
    }

    /**
     * Mostra o formulário para criar uma nova página.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.pages.create'); // Retorna a view do formulário de criação
    }

    /**
     * Armazena uma nova página no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Valida os dados da requisição
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'nullable|string|unique:pages,slug|max:255', // Slug é opcional, mas se fornecido, deve ser único
        ]);

        // Se o slug não for fornecido, gera um a partir do título
        $slug = $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title'));

        // Garante que o slug é único, adicionando um sufixo se necessário
        $originalSlug = $slug;
        $count = 1;
        while (Page::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Cria a nova página no banco de dados
        Page::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'slug' => $slug,
        ]);

        // Redireciona para a lista de páginas com uma mensagem de sucesso
        return redirect()->route('admin.pages.index')->with('success', 'Página criada com sucesso!');
    }

    /**
     * Exibe uma página específica.
     * (Normalmente não é usado para páginas administrativas, mas pode ser útil para pré-visualização)
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\View\View
     */
    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page')); // Retorna a view de exibição da página
    }

    /**
     * Mostra o formulário para editar uma página existente.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\View\View
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page')); // Retorna a view do formulário de edição
    }

    /**
     * Atualiza uma página existente no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Page $page)
    {
        // Valida os dados da requisição
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            // O slug deve ser único, exceto para a página atual
            'slug' => 'required|string|unique:pages,slug,' . $page->id . '|max:255',
        ]);

        // Atualiza a página no banco de dados
        $page->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'slug' => Str::slug($request->input('slug')), // Garante que o slug seja formatado corretamente
        ]);

        // Redireciona para a lista de páginas com uma mensagem de sucesso
        return redirect()->route('admin.pages.index')->with('success', 'Página atualizada com sucesso!');
    }

    /**
     * Remove uma página do banco de dados.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Page $page)
    {
        $page->delete(); // Deleta a página do banco de dados
        return redirect()->route('admin.pages.index')->with('success', 'Página excluída com sucesso!'); // Redireciona com mensagem
    }
}