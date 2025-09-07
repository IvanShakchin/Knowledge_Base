@extends('layouts.app')

@section('title', 'Create Article')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New Article</h5>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Back to Articles</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                id="title" name="title" value="{{ old('title') }}" required>
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
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="published_auth" {{ old('status') == 'published_auth' ? 'selected' : '' }}>Published Auth</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
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
                                        {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
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
                            <button type="submit" class="btn btn-primary">Create Article</button>
                            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>       
                    </form>
                </div>
            </div>
        </div>
  
    
    </div>
</div>
@endsection

