@extends('layouts.app')

@section('title', $memorial->exists ? 'Редактировать мемориал' : 'Создать мемориал')

@section('content')
<div class="bg-gray-50 min-h-screen py-6" x-data="{ activeTab: 'basic' }">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Основной контент -->
            <main class="flex-1">
                <div class="bg-white rounded-xl shadow-md p-8">
                    <h1 class="text-2xl font-bold text-slate-700 mb-6">
                        {{ $memorial->exists ? 'Редактировать мемориал' : 'Создать мемориал' }}
                    </h1>

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ $memorial->exists ? route('memorial.update', $memorial->id) : route('memorial.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @if($memorial->exists)
                            @method('PUT')
                        @endif

                        <!-- Вкладки -->
                        @include('memorial.edit.tabs.basic')
                        @include('memorial.edit.tabs.biography')
                        @include('memorial.edit.tabs.burial')
                        @include('memorial.edit.tabs.media')
                        @include('memorial.edit.tabs.people')
                        @include('memorial.edit.tabs.settings')
                        @include('memorial.edit.tabs.qrcode')

                        <!-- Кнопки сохранения -->
                        <div class="flex gap-4 pt-6 border-t">
                            <button type="submit" name="action" value="draft" class="flex-1 px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors font-medium">
                                Сохранить как черновик
                            </button>
                            <button type="submit" name="action" value="publish" class="flex-1 px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors font-medium">
                                {{ $memorial->exists ? 'Сохранить и опубликовать' : 'Опубликовать' }}
                            </button>
                        </div>
                    </form>
                </div>
            </main>

            @include('memorial.edit.sidebar')
        </div>
    </div>
</div>

@include('memorial.edit.scripts')
@endsection
