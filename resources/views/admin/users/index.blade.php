@extends('layouts.app')

@section('title', 'Управление пользователями')

@section('content')   
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Управление пользователями</h5>
                </div>

                <div class="card-body">
                    <h6 class="mb-3">Пользователи, ожидающие подтверждения</h6>
                    
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="background: #c6c6c6;">Имя</th>
                                        <th style="background: #c6c6c6;">Email</th>
                                        <th style="background: #c6c6c6;">Дата регистрации</th>
                                        <th style="background: #c6c6c6;">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success">Подтвердить</button>
                                                </form>
                                                <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">Отклонить</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Нет пользователей, ожидающих подтверждения.
                        </div>
                    @endif

                    <hr class="my-4">

                    <h6 class="mb-3">Подтвержденные пользователи</h6>
                    
                    @if($approvedUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th  style="background: #c6c6c6;">Имя</th>
                                        <th  style="background: #c6c6c6;">Email</th>
                                        <th  style="background: #c6c6c6;">Роль</th>
                                        <th  style="background: #c6c6c6;">Дата регистрации</th>
                                        <th  style="background: #c6c6c6;">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approvedUsers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                                                    {{ $user->role === 'admin' ? 'Администратор' : 'Пользователь' }}
                                                </span>
                                            </td>
                                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                @if($user->role !== 'superadmin')
                                                    @if($user->role === 'admin')
                                                        <form action="{{ route('admin.users.removeAdmin', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-warning">Забрать права админа</button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('admin.users.makeAdmin', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-primary">Сделать админом</button>
                                                        </form>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Суперадмин</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Нет подтвержденных пользователей.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection