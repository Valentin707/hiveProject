<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0c">
    <title>Node Registry | The Hive Project</title>
    @vite('resources/js/applications.js')
</head>
<body>
    <main class="registry-shell">
        <a class="return-link" href="{{ route('dashboard') }}">Return to Core</a>
        <header class="registry-heading">
            <div>
                <p class="eyebrow">Identity Core // Service Mesh</p>
                <h1>Node Registry</h1>
                <p class="lead">Register and configure the applications connected to this identity core.</p>
            </div>
            <span class="registry-count">{{ $applications->count() }} REGISTERED NODES</span>
        </header>

        @if (session('status'))
            <div class="notice success" role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="notice error" role="alert">{{ $errors->first() }}</div>
        @endif

        <section class="registry-panel create-panel" aria-labelledby="create-heading">
            <div class="panel-heading">
                <div>
                    <span class="panel-index">01 // REGISTER</span>
                    <h2 id="create-heading">New Satellite Application</h2>
                </div>
                <span class="panel-hint">DATABASE REGISTRY</span>
            </div>

            <form class="create-form" method="POST" action="{{ route('applications.store') }}">
                @csrf

                <div class="field">
                    <label for="name">Application name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" maxlength="255" required>
                    @error('name') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="url">Application URL</label>
                    <input id="url" name="url" type="url" value="{{ old('url') }}" placeholder="https://tasks.hive.test" maxlength="255" required>
                    @error('url') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="field field-health-url">
                    <label for="health_check_url">Health check URL <span>optional</span></label>
                    <input id="health_check_url" name="health_check_url" type="url" value="{{ old('health_check_url') }}" placeholder="https://tasks.hive.test/up" maxlength="255">
                    @error('health_check_url') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="oauth_client_id">Passport OAuth client <span>optional</span></label>
                    <select id="oauth_client_id" name="oauth_client_id">
                        <option value="">No linked OAuth client</option>
                        @foreach ($oauthClients as $oauthClient)
                            <option value="{{ $oauthClient->getKey() }}" @selected(old('oauth_client_id') === (string) $oauthClient->getKey())>
                                {{ $oauthClient->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('oauth_client_id') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="field field-description">
                    <label for="description">HUD subtitle <span>optional</span></label>
                    <input id="description" name="description" type="text" value="{{ old('description') }}" maxlength="255" placeholder="Task orchestration">
                    @error('description') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="field field-order">
                    <label for="sort_order">Sort order</label>
                    <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', 0) }}" min="0" step="1" required>
                    @error('sort_order') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="field field-active">
                    <label class="checkbox-label" for="create-is-active">
                        <input type="hidden" name="is_active" value="0">
                        <input id="create-is-active" name="is_active" type="checkbox" value="1" @checked(old('is_active', true))>
                        <span>Active on dashboard</span>
                    </label>
                    @error('is_active') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <button class="primary-action" type="submit">Register node</button>
            </form>
        </section>

        <section class="registry-panel nodes-panel" aria-labelledby="nodes-heading">
            <div class="panel-heading">
                <div>
                    <span class="panel-index">02 // CONNECTED SERVICES</span>
                    <h2 id="nodes-heading">Registered Applications</h2>
                </div>
                <span class="panel-hint">NODE INVENTORY</span>
            </div>

            @if ($applications->isEmpty())
                <p class="empty-state">No application nodes are registered yet. Add a satellite above to populate the dashboard.</p>
            @else
                <div class="table-scroll">
                    <table class="applications-table">
                        <thead>
                            <tr>
                                <th scope="col">Node</th>
                                <th scope="col">Endpoint</th>
                                <th scope="col">OAuth client</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $application)
                                @php($oauthClientName = $oauthClients->firstWhere('id', $application->oauth_client_id)?->name)
                                <tr>
                                    <td class="node-cell">
                                        <strong>{{ $application->name }}</strong>
                                        <span>{{ $application->description ?: 'No HUD subtitle' }}</span>
                                        <small>ORDER {{ str_pad((string) $application->sort_order, 2, '0', STR_PAD_LEFT) }}</small>
                                    </td>
                                    <td class="endpoint-cell">{{ $application->url }}</td>
                                    <td class="oauth-cell">{{ $oauthClientName ?: 'UNLINKED' }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('applications.toggle', $application) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="status-badge {{ $application->is_active ? 'active' : 'inactive' }}" type="submit">
                                                {{ $application->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="row-actions">
                                        <button class="edit-action" type="button" data-open-application-modal="edit-application-{{ $application->id }}" aria-haspopup="dialog">Edit</button>
                                        <form method="POST" action="{{ route('applications.destroy', $application) }}" data-confirm-delete data-application-name="{{ $application->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="delete-action" type="submit" aria-label="Delete {{ $application->name }}">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @foreach ($applications as $application)
                    @php($editingThisApplication = (string) old('edit_application_id') === (string) $application->id)
                    <dialog class="edit-modal" id="edit-application-{{ $application->id }}" data-application-modal @if ($editingThisApplication) data-open-on-load="true" @endif>
                        <section class="modal-panel" aria-labelledby="edit-heading-{{ $application->id }}">
                            <header class="modal-heading">
                                <div>
                                    <span class="panel-index">NODE // CONFIGURATION</span>
                                    <h2 id="edit-heading-{{ $application->id }}">Edit {{ $application->name }}</h2>
                                </div>
                                <button class="modal-close" type="button" data-close-application-modal aria-label="Close edit dialog">×</button>
                            </header>

                            <form class="edit-form" method="POST" action="{{ route('applications.update', $application) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="edit_application_id" value="{{ $application->id }}">

                                <div class="field">
                                    <label for="edit-{{ $application->id }}-name">Application name</label>
                                    <input id="edit-{{ $application->id }}-name" name="name" type="text" value="{{ $editingThisApplication ? old('name', $application->name) : $application->name }}" maxlength="255" required>
                                    @if ($editingThisApplication) @error('name') <span class="field-error">{{ $message }}</span> @enderror @endif
                                </div>

                                <div class="field">
                                    <label for="edit-{{ $application->id }}-url">Application URL</label>
                                    <input id="edit-{{ $application->id }}-url" name="url" type="url" value="{{ $editingThisApplication ? old('url', $application->url) : $application->url }}" maxlength="255" required>
                                    @if ($editingThisApplication) @error('url') <span class="field-error">{{ $message }}</span> @enderror @endif
                                </div>

                                <div class="field">
                                    <label for="edit-{{ $application->id }}-health-url">Health check URL <span>optional</span></label>
                                    <input id="edit-{{ $application->id }}-health-url" name="health_check_url" type="url" value="{{ $editingThisApplication ? old('health_check_url', $application->health_check_url) : $application->health_check_url }}" placeholder="Defaults to application URL" maxlength="255">
                                    @if ($editingThisApplication) @error('health_check_url') <span class="field-error">{{ $message }}</span> @enderror @endif
                                </div>

                                <div class="field">
                                    <label for="edit-{{ $application->id }}-oauth-client">Passport OAuth client <span>optional</span></label>
                                    <select id="edit-{{ $application->id }}-oauth-client" name="oauth_client_id">
                                        <option value="">No linked OAuth client</option>
                                        @foreach ($oauthClients as $oauthClient)
                                            <option value="{{ $oauthClient->getKey() }}" @selected(($editingThisApplication ? old('oauth_client_id', $application->oauth_client_id) : $application->oauth_client_id) === (string) $oauthClient->getKey())>
                                                {{ $oauthClient->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($editingThisApplication) @error('oauth_client_id') <span class="field-error">{{ $message }}</span> @enderror @endif
                                </div>

                                <div class="field">
                                    <label for="edit-{{ $application->id }}-description">HUD subtitle <span>optional</span></label>
                                    <input id="edit-{{ $application->id }}-description" name="description" type="text" value="{{ $editingThisApplication ? old('description', $application->description) : $application->description }}" maxlength="255">
                                    @if ($editingThisApplication) @error('description') <span class="field-error">{{ $message }}</span> @enderror @endif
                                </div>

                                <div class="field">
                                    <label for="edit-{{ $application->id }}-sort-order">Sort order</label>
                                    <input id="edit-{{ $application->id }}-sort-order" name="sort_order" type="number" value="{{ $editingThisApplication ? old('sort_order', $application->sort_order) : $application->sort_order }}" min="0" step="1" required>
                                    @if ($editingThisApplication) @error('sort_order') <span class="field-error">{{ $message }}</span> @enderror @endif
                                </div>

                                <div class="field field-active">
                                    <label class="checkbox-label" for="edit-{{ $application->id }}-is-active">
                                        <input type="hidden" name="is_active" value="0">
                                        <input id="edit-{{ $application->id }}-is-active" name="is_active" type="checkbox" value="1" @checked($editingThisApplication ? old('is_active', $application->is_active) : $application->is_active)>
                                        <span>Active on dashboard</span>
                                    </label>
                                    @if ($editingThisApplication) @error('is_active') <span class="field-error">{{ $message }}</span> @enderror @endif
                                </div>

                                <footer class="modal-actions">
                                    <button class="secondary-action" type="button" data-close-application-modal>Cancel</button>
                                    <button class="primary-action" type="submit">Save node</button>
                                </footer>
                            </form>
                        </section>
                    </dialog>
                @endforeach
            @endif
        </section>
    </main>
</body>
</html>
