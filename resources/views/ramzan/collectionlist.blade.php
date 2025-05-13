@extends('layouts.app')

@section('content')
<style>
    .btn-sm {
        width: 10px; 
        height: 10px; 
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<div class="container">
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h2 class="text-center text-md-start">Ramadan Collection List</h2>
        </div>
        <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
            <div class="d-flex justify-content-end">
                <a class="btn btn-success btn-small me-2" href="{{ route('collection.create') }}">
                    <i class="fa fa-plus"></i>
                </a>
                <a href="{{ route('export.collections', request()->all()) }}" class="btn btn-success btn-small">
                    <i class="fas fa-share-square"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('collectionlist') }}">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <td>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="{{ request('name') }}" class="form-control" placeholder="Name">
                    </td>
                    <td>
                        <label for="contact">Contact:</label>
                        <input type="text" id="contact" name="contact" value="{{ request('contact') }}" class="form-control" placeholder="Contact">
                    </td>
                    <td>
                        <label for="receipt_book">Receipt Number:</label>
                        <input type="text" id="receipt_book" name="receipt_book" value="{{ request('receipt_book') }}" class="form-control" placeholder="Receipt Number">
                    </td>
                    <td>
                        <label for="donationcategory">Category:</label>
                        <select name="donationcategory" class="form-control">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}" {{ request('donationcategory') == $category->name ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <!-- Payment Mode Filter -->
                    <td>
                        <label for="payment_mode">Payment Mode:</label>
                        <select name="payment_mode" class="form-control">
                            <option value="">Select Payment Mode</option>
                            <option value="Cash" {{ request('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Online" {{ request('payment_mode') == 'Online' ? 'selected' : '' }}>Online</option>
                        </select>
                    </td>
                    <td>
                        <label for="collected_by">Collected By:</label>
                        <select name="collected_by" class="form-control">
                            <option value="">Collected By</option>
                            @foreach($collectedUsers as $user)
                                <option value="{{ $user->id }}" {{ request('collected_by') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="text-nowrap">
                        <button type="submit" class="btn btn-primary mt-4">Filter</button>
                        <a href="{{ route('collectionlist') }}" class="btn btn-secondary mt-4 ms-2">Reset</a>
                    </td>
                </tr>
            </table>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Collection List -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    
                    <!-- Sortable Date Column -->
                    <th>
    Date
    <a href="{{ route('collectionlist', ['sort_by' => 'date', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'date' ? 'desc' : 'asc'] + request()->except('page')) }}">
        <i class="fa fa-sort{{ request('sort_by') === 'date' ? '-' . request('sort_order') : '' }}"></i>
    </a>
</th>

                    <!-- Sortable Name Column -->
                    <th>
                        Name
                        <a href="{{ route('collectionlist', ['sort_by' => 'name', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'name' ? 'desc' : 'asc'] + request()->except('page')) }}">
        <i class="fa fa-sort{{ request('sort_by') === 'name' ? '-' . request('sort_order') : '' }}"></i>
    </a>
                            @if(request('sort_by') === 'name')
                                <i class="fa fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>

                    <th>Donation Category</th>
                    <th>Payment Mode</th>
                    <th>Collected By</th>
                    
                    <!-- Sortable Amount Column -->
                    <th>
                        Amount
                        <a href="{{ route('collectionlist', ['sort_by' => 'amount', 'sort_order' => request('sort_order') === 'asc' && request('sort_by') === 'amount' ? 'desc' : 'asc'] + request()->except('page')) }}">
        <i class="fa fa-sort{{ request('sort_by') === 'amount' ? '-' . request('sort_order') : '' }}"></i>
    </a>
                            @if(request('sort_by') === 'amount')
                                <i class="fa fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($collections as $collection)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $collection->date }}</td>
                        <td>{{ $collection->name }}</td>
                        <td>{{ $collection->donationcategory }}</td>
                        <td>{{ $collection->payment_mode }}</td>
                        <td>{{ $collection->user->name ?? 'N/A' }}</td>
                        <td>{{ $collection->amount }}</td>
                        <td>
                            <div class="d-flex justify-content-center flex-wrap gap-1">
                                <a class="btn btn-info btn-sm d-flex align-items-center justify-content-center" 
                                   style="width: 25px; height: 25px;" 
                                   href="{{ route('collection.show', $collection->id) }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('collection.edit', $collection->id) }}" 
                                   class="btn btn-primary btn-sm d-flex align-items-center justify-content-center" 
                                   style="width: 25px; height: 25px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a class="btn btn-success btn-sm d-flex align-items-center justify-content-center" 
                                   style="width: 25px; height: 25px;" 
                                   onclick="openPDF('{{ route('collection.pdf', $collection->id) }}')" 
                                   href="javascript:void(0);">
                                    <i class="fas fa-file"></i>
                                </a>
                                <form action="{{ route('collection.destroy', $collection->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" 
                                            style="width: 25px; height: 25px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    <div class="d-flex justify-content-center mt-3">
        {{ $collections->links() }}
    </div>
</div>
@endsection

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    function openPDF(url) {
        var win = window.open(url, '_blank');
        if (win) {
            win.focus();
        } else {
            alert('Please allow pop-ups for this website');
        }
    }
</script>
