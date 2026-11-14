<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Passport\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(): View
    {
        $applications = Application::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $oauthClients = Client::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('applications.index', compact('applications', 'oauthClients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        Application::query()->create($validated);

        return to_route('applications.index')->with('status', 'Satellite application registered.');
    }

    public function update(Request $request, Application $application): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $application->fill($validated)->save();

        return to_route('applications.index')->with('status', 'Satellite application updated.');
    }

    public function destroy(Application $application): RedirectResponse
    {
        $application->delete();

        return to_route('applications.index')->with('status', 'Satellite application removed.');
    }

    public function toggle(Application $application): RedirectResponse
    {
        $application->update(['is_active' => ! $application->is_active]);

        return to_route('applications.index')->with(
            'status',
            $application->is_active ? 'Satellite application activated.' : 'Satellite application deactivated.',
        );
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url:http,https', 'max:255'],
            'health_check_url' => ['nullable', 'url:http,https', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'oauth_client_id' => ['nullable', 'string', 'exists:oauth_clients,id'],
        ];
    }
}
