<?php

use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $statusFilter = '';
    public string $search = '';

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updateStatus(int $ticketId, string $newStatus): void
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->update(['status' => $newStatus]);
    }

    public function with(): array
    {
        return [
            'tickets' => Ticket::with(['user', 'category'])
                ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
                ->when($this->search, fn($query) => $query->where('title', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(10),
        ];
    }
};
?>

<div>
    <div class="row mb-3">
        <div class="col-md-4">
            <select wire:model.live="statusFilter" class="form-control">
                <option value="">All Statuses</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="closed">Closed</option>
            </select>
        </div>
        <div class="col-md-4">
            <input wire:model.live.debounce.400ms="search" type="text" class="form-control"
                placeholder="Search by title...">
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Customer</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr wire:key="ticket-{{ $ticket->id }}">
                            <td>{{ $ticket->title }}</td>
                            <td>{{ $ticket->user->name }}</td>
                            <td>{{ $ticket->category->name }}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $ticket->priority === 'high' ? 'danger' : ($ticket->priority === 'medium' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td>
                                <select wire:change="updateStatus({{ $ticket->id }}, $event.target.value)"
                                    class="form-control form-control-sm">
                                    <option value="open" @selected($ticket->status === 'open')>Open</option>
                                    <option value="in_progress" @selected($ticket->status === 'in_progress')>In Progress
                                    </option>
                                    <option value="closed" @selected($ticket->status === 'closed')>Closed</option>
                                </select>
                            </td>
                            <td>{{ $ticket->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $tickets->links() }}
        </div>
    </div>
</div>