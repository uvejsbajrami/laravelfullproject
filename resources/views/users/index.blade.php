<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Users') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
            @if($users->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role(s)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if(Spatie\Permission\Models\Role::count() > 0)
                                @foreach(Spatie\Permission\Models\Role::pluck('name')->all() as $role)
                                    @if(in_array($role, $user->roles()->pluck('name')->all()))
                                        <span class="ms-2">{{ $role }}</span>
                                    @else
                                   <form method="POST" class="d-inline ms-2" action="{{ route('users.update', ['user' => $user->id]) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="role" value="{{ $role }}">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-plus"></i> {{ $role }}
                                        </button>
                                    </form>
                                    @endif
                                @endforeach
                            @else
                                N/D
                            @endif
                        </td>
                        <td>
                            <form method="POST" class="d-inline ms-2" action="{{ route('users.destroy', ['user' => $user->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                0 users
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
