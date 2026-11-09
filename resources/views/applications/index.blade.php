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
                <span class="panel-hint">EDIT FIELDS INLINE</span>
            </div>

            @if ($applications->isEmpty())
                <p class="empty-state">No application nodes are registered yet. Add a satellite above to populate the dashboard.</p>
            @else
                <div class="table-scroll">
                    <table class="applications-table">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Endpoint</th>
                                <th scope="col">HUD subtitle</th>
                                <th scope="col">Order</th>
                                <th scope="col">Status</th>
                                <th scope="col"><span class="visually-hidden">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $application)
                                @php($updateFormId = 'update-application-'.$application->id)
                                <tr>
                                    <td>
                                        <form id="{{ $updateFormId }}" method="POST" action="{{ route('applications.update', $application) }}">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                        <input class="table-input name-input" form="{{ $updateFormId }}" name="name" type="text" value="{{ $application->name }}" aria-label="Name for {{ $application->name }}" maxlength="255" required>
                                    </td>
                                    <td><input class="table-input url-input" form="{{ $updateFormId }}" name="url" type="url" value="{{ $application->url }}" aria-label="URL for {{ $application->name }}" maxlength="255" required></td>
                                    <td><input class="table-input description-input" form="{{ $updateFormId }}" name="description" type="text" value="{{ $application->description }}" aria-label="HUD subtitle for {{ $application->name }}" maxlength="255"></td>
                                    <td><input class="table-input order-input" form="{{ $updateFormId }}" name="sort_order" type="number" value="{{ $application->sort_order }}" aria-label="Sort order for {{ $application->name }}" min="0" step="1" required></td>
                                    <td>
                                        <form method="POST" action="{{ route('applications.toggle', $application) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="status-badge {{ $application->is_active ? 'active' : 'inactive' }}" type="submit">
                                                {{ $application->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                        <input form="{{ $updateFormId }}" type="hidden" name="is_active" value="0">
                                        <input class="visually-hidden" form="{{ $updateFormId }}" type="checkbox" name="is_active" value="1" @checked($application->is_active)>
                                    </td>
                                    <td class="row-actions">
                                        <button class="save-action" type="submit" form="{{ $updateFormId }}">Save</button>
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
            @endif
        </section>
    </main>
</body>
</html>
