<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0c">
    <title>User Management | The Hive Project</title>
    @vite('resources/js/users.js')
</head>
<body>
    <main class="users-shell">
        <a class="return-link" href="{{ route('dashboard') }}">Return to Core</a>

        <header class="users-heading">
            <div>
                <p class="eyebrow">Identity Core // Access Control</p>
                <h1>User Management</h1>
                <p class="lead">Provision operator accounts and manage access to the central identity provider.</p>
            </div>
            <div class="heading-actions">
                <span class="user-count">{{ $users->count() }} REGISTERED USERS</span>
                <button class="primary-action open-user-modal" type="button" data-open-user-modal aria-haspopup="dialog" aria-controls="create-user-modal">
                    <span aria-hidden="true">+</span> Create User
                </button>
            </div>
        </header>

        @if (session('status'))
            <div class="notice success" role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="notice error" role="alert">{{ $errors->first() }}</div>
        @endif

        <dialog class="user-modal" id="create-user-modal" data-user-modal data-open-on-load="{{ $errors->hasAny(['name', 'email', 'password', 'password_confirmation', 'role', 'application_ids', 'application_ids.*']) ? 'true' : 'false' }}" aria-labelledby="create-heading">
            <div class="modal-panel">
                <header class="modal-heading">
                    <div>
                        <span class="panel-index">01 // PROVISION</span>
                        <h2 id="create-heading">Add Operator Node</h2>
                        <p class="modal-copy">Create a central identity account and assign its access role.</p>
                    </div>
                    <button class="modal-close" type="button" data-close-user-modal aria-label="Close create user dialog">×</button>
                </header>

                <form class="create-form" method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="field">
                        <label for="name">Operator name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" maxlength="255" required autofocus>
                        @error('name') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="email">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required>
                        @error('email') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="password">Initial password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" minlength="12" required>
                        @error('password') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Confirm password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="12" required>
                    </div>

                    <div class="field">
                        <label for="role">Access role</label>
                        <select id="role" name="role" required>
                            <option value="operator" @selected(old('role', 'operator') === 'operator')>Operator</option>
                            <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        </select>
                        @error('role') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <fieldset class="field clearance-field">
                        <legend>Initial node clearance <span>active applications</span></legend>
                        @if ($activeApplications->isEmpty())
                            <p class="clearance-empty">No active applications are available for assignment.</p>
                        @else
                            <div class="clearance-options">
                                @foreach ($activeApplications as $application)
                                    <label class="clearance-option" for="create-application-{{ $application->id }}">
                                        <input
                                            id="create-application-{{ $application->id }}"
                                            name="application_ids[]"
                                            type="checkbox"
                                            value="{{ $application->id }}"
                                            @checked(in_array((string) $application->id, array_map('strval', (array) old('application_ids', [])), true))
                                        >
                                        <span>{{ $application->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                        @error('application_ids') <span class="field-error">{{ $message }}</span> @enderror
                        @error('application_ids.*') <span class="field-error">{{ $message }}</span> @enderror
                    </fieldset>

                    <footer class="modal-actions">
                        <button class="secondary-action" type="button" data-close-user-modal>Cancel</button>
                        <button class="primary-action" type="submit">Create user</button>
                    </footer>
                </form>
            </div>
        </dialog>

        <section class="users-panel directory-panel" aria-labelledby="directory-heading">
            <div class="panel-heading">
                <div>
                    <span class="panel-index">02 // REGISTERED ACCOUNTS</span>
                    <h2 id="directory-heading">Hub User Directory</h2>
                </div>
                <span class="panel-hint">ROLE CONTROL</span>
            </div>

            @if ($users->isEmpty())
                <p class="empty-state">No operator accounts have been registered.</p>
            @else
                <div class="table-scroll">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Access role</th>
                                <th scope="col">Node clearance</th>
                                <th scope="col">Created</th>
                                <th scope="col"><span class="visually-hidden">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <span class="account-name">{{ $user->name }}</span>
                                        @if ($user->is(auth()->user()))
                                            <span class="self-marker">CURRENT ACCOUNT</span>
                                        @endif
                                    </td>
                                    <td class="account-email">{{ $user->email }}</td>
                                    <td>
                                        <span class="role-badge {{ $user->role === 'admin' ? 'admin' : 'operator' }}">
                                            <span class="role-light" aria-hidden="true"></span>
                                            {{ $user->role === 'admin' ? 'Admin' : 'Operator' }}
                                        </span>
                                    </td>
                                    <td class="clearance-cell">
                                        @if ($user->role === 'admin')
                                            <span class="clearance-badge admin-clearance">FULL CLEARANCE (ADMIN)</span>
                                        @else
                                            <div class="clearance-badges">
                                                @forelse ($user->applications as $application)
                                                    <span class="clearance-badge {{ $application->is_active ? 'active' : 'inactive' }}">
                                                        {{ $application->name }}
                                                    </span>
                                                @empty
                                                    <span class="clearance-badge no-clearance">NO NODE CLEARANCE</span>
                                                @endforelse
                                            </div>

                                            <details class="clearance-editor">
                                                <summary>Manage node access</summary>
                                                <form method="POST" action="{{ route('users.applications.update', $user) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <fieldset class="clearance-options editor-options">
                                                        <legend class="visually-hidden">Applications assigned to {{ $user->name }}</legend>
                                                        @forelse ($applications as $application)
                                                            <label class="clearance-option" for="user-{{ $user->id }}-application-{{ $application->id }}">
                                                                <input
                                                                    id="user-{{ $user->id }}-application-{{ $application->id }}"
                                                                    name="application_ids[]"
                                                                    type="checkbox"
                                                                    value="{{ $application->id }}"
                                                                    @checked($user->applications->contains('id', $application->id))
                                                                >
                                                                <span>{{ $application->name }}</span>
                                                                @unless ($application->is_active)
                                                                    <small>Inactive</small>
                                                                @endunless
                                                            </label>
                                                        @empty
                                                            <p class="clearance-empty">No applications are registered.</p>
                                                        @endforelse
                                                    </fieldset>
                                                    <button class="clearance-save" type="submit">Save clearance</button>
                                                </form>
                                            </details>
                                        @endif
                                    </td>
                                    <td class="created-date">{{ $user->created_at?->format('Y-m-d H:i') ?? 'Unknown' }}</td>
                                    <td class="row-actions">
                                        @if ($user->is(auth()->user()))
                                            <span class="protected-label">SELF-PROTECTED</span>
                                        @else
                                            <form method="POST" action="{{ route('users.role.update', $user) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="role-action" type="submit">
                                                    {{ $user->role === 'admin' ? 'Demote' : 'Promote' }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('users.destroy', $user) }}" data-confirm-delete data-user-name="{{ $user->name }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="delete-action" type="submit" aria-label="Delete {{ $user->name }}">Delete</button>
                                            </form>
                                        @endif
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
