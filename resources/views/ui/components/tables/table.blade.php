@php
  $headers = $headers ?? ['ID', 'Name', 'Status', 'Role', 'Actions'];
  $rows = $rows ?? [
    ['1', 'Alex Mercer', 'Active', 'Developer'],
    ['2', 'Sarah Connor', 'Inactive', 'Administrator'],
    ['3', 'John Doe', 'Active', 'Manager'],
  ];
  $class = $class ?? '';
@endphp

<div class="synkra-table-container {{ $class }}">
  <table class="synkra-table">
    <thead>
      <tr>
        @foreach($headers as $header)
          <th>{{ $header }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($rows as $row)
        <tr>
          @foreach($row as $cell)
            <td>
              @if($cell instanceof \Illuminate\Contracts\Support\Htmlable)
                {!! $cell !!}
              @elseif(is_string($cell) && strtolower($cell) === 'active')
                <span class="synkra-badge-pill synkra-badge-success">Active</span>
              @elseif(is_string($cell) && strtolower($cell) === 'inactive')
                <span class="synkra-badge-pill synkra-badge-danger">Inactive</span>
              @else
                {{ $cell }}
              @endif
            </td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<style>
.synkra-table-container {
  width: 100%;
  overflow-x: auto;
  border: 1px solid var(--border);
  border-radius: 12px;
  background-color: var(--surface);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
}

.synkra-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
  font-size: 0.875rem;
  color: var(--body-text);
}

.synkra-table th {
  background-color: var(--surface-secondary);
  color: var(--text-secondary);
  font-weight: 700;
  padding: 1rem 1.25rem;
  border-bottom: 1px solid var(--border);
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 0.05em;
  user-select: none;
}

.synkra-table td {
  padding: 1rem 1.25rem;
  border-bottom: 1px solid var(--divider);
  vertical-align: middle;
}

.synkra-table tbody tr:last-child td {
  border-bottom: none;
}

.synkra-table tbody tr:hover {
  background-color: var(--surface-secondary);
}

.synkra-table-actions {
  display: flex;
  gap: 0.5rem;
}

.synkra-table-action-btn {
  background: transparent;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
  transition: all 0.2s;
}

.synkra-table-action-btn:hover {
  color: var(--primary);
  background-color: var(--surface);
}

/* Badge helpers if used inside the table */
.synkra-badge-pill {
  display: inline-flex;
  align-items: center;
  padding: 2px 8px;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 700;
}

.synkra-badge-success {
  background-color: rgba(34, 197, 94, 0.1);
  color: #166534;
}

.synkra-badge-danger {
  background-color: rgba(239, 68, 68, 0.1);
  color: #991b1b;
}
</style>
