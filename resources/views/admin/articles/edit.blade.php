@extends('layouts.app')

@section('title', 'Edit Article')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Article: {{ $article->title }}</h5>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Back to Articles</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                id="title" name="title" value="{{ old('title', $article->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content *</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                id="content" name="content" rows="10" required>{{ old('content', $article->content ?? '') }}</textarea> 
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category *</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                        <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="published_auth" {{ old('status', $article->status) == 'published_auth' ? 'selected' : '' }}>Published Auth</option>
                                        <option value="pending" {{ old('status', $article->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    </select>                                  
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <select class="form-select @error('tags') is-invalid @enderror" 
                                id="tags" name="tags[]" multiple size="3">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" 
                                        {{ in_array($tag->id, old('tags', $article->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Hold Ctrl/Cmd to select multiple tags</div>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="files" class="form-label">Upload Files (JPG, PNG, PDF)</label>
                            <input class="form-control @error('files') is-invalid @enderror" 
                                type="file" id="files" name="files[]" multiple accept=".jpg,.jpeg,.png,.pdf">
                            @error('files')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">You can select multiple files. Maximum size: 5MB per file.</div>
                        </div> 
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Update Article</button>
                            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($article->media->count() > 0)
            <div class="mb-3">
                <label class="form-label">Uploaded Files:</label>
                <div class="row">
                    @foreach($article->media as $media)
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            @if($media->isImage())
                            <img src="{{ Storage::url($media->path) }}" class="card-img-top" alt="{{ $media->original_name }}">
                            @elseif($media->isPdf())
                            <div class="card-body text-center">
                                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                            </div>
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">{{ Str::limit($media->original_name, 20) }}</h6>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ Storage::url($media->path) }}" target="_blank" class="btn btn-primary">View</a>
                                    <form action="{{ route('admin.articles.files.destroy', [$article, $media]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- История изменений -->
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h5>История изменений</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="height: 200px;">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Changes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($histories as $history)
                                <tr>
                                    <td>{{ $history->created_at->format('d.m.Y H:i') }}</td>
                                    <td>{{ $history->user->name }}</td>
                                    <td>
                                        @if($history->changes)
                                            @foreach($history->changes as $field => $change)
                                                @if($field === 'type')
                                                    @if($change === 'created')
                                                        <span class="badge bg-success">Created</span>
                                                    @elseif($change === 'restored')
                                                        <span class="badge bg-info">Восстановлено из версии</span>
                                                    @endif
                                                @else
                                                    @if(is_array($change) && isset($change['old']) && isset($change['new']))
                                                        <strong>{{ $field }}:</strong> 
                                                        {{ is_array($change['old']) ? implode(', ', $change['old']) : $change['old'] }}
                                                        → 
                                                        {{ is_array($change['new']) ? implode(', ', $change['new']) : $change['new'] }}
                                                        <br>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="text-muted">Изменений не обнаружено</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$loop->first)
                                        <form action="{{ route('admin.articles.restore', [$article, $history]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Restore this version?')">Восстановить</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection