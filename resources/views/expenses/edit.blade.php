@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Expense ') }} <strong>({{ $expense->id }})</strong></div>

                <div class="card-body">
                    <form method="POST" action="/expenses/{{ $expense->id }}">
                        @csrf
                        @method('PATCH')

                        <div class="row mb-3">
                            <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Expense Details') }}</label>

                            <div class="col-md-6">
                                <input class="form-control @error('description') is-invalid @enderror" id="description" type="text" required name="description" value="{{ old('description') ?? $expense->description }}" autocomplete="on" autofocus>

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="amount" class="col-md-4 col-form-label text-md-end">{{ __('Amount') }}</label>

                            <div class="col-md-6">
                                <input class="form-control @error('amount') is-invalid @enderror" id="amount" type="number" step=".01" required name="amount" value="{{ old('amount') ?? $expense->amount }}" autocomplete="on">

                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="date" class="col-md-4 col-form-label text-md-end">{{ __('Date') }}</label>

                            <div class="col-md-6">
                                <input class="form-control @error('date') is-invalid @enderror" id="date" type="date" name="date" value="{{ old('date') ?? $expense->date }}" required autocomplete="on">

                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="expense_category_id" class="col-md-4 col-form-label text-md-end">{{ __('Category') }}</label>

                            <div class="col-md-6">
                                <select class="form-control @error('category_id') is-invalid @enderror" id="expense_category_id" name="expense_category_id" required>
                                    @foreach($categories as $category)
                                        <option @selected($expense->expense_category_id == $category->id) value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                @error('expense_category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
