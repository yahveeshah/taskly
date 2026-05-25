<x-layout>
    <x-slot name="title">My History</x-slot>

    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <h2 class="section-title" style="margin: 0;">Completed Tasks</h2>
        
        @if(count($tasks) > 0)
        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <button type="button" class="ui-button ui-button-secondary ui-button-sm" id="selectAllBtn" onclick="toggleSelectAll()">
                Select All
            </button>
            
            <form id="deleteSelectedForm" action="{{ route('tasks.history.selected') }}" method="POST" style="margin: 0;">
                @csrf
                @method('DELETE')
                <div id="hiddenCheckboxes"></div>
                <button type="button" class="ui-button ui-button-warning ui-button-sm" id="deleteSelectedBtn" onclick="submitSelected()" disabled>
                    Delete Selected
                </button>
            </form>
            
            <form action="{{ route('tasks.history.all') }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete all completed tasks? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="ui-button ui-button-danger ui-button-sm">
                    Delete All
                </button>
            </form>
        </div>
        @endif
    </div>

    @if(count($tasks) === 0)
        <div class="ui-card" style="padding: 3rem 2rem; text-align: center;">
            <div style="font-size: 3rem; color: var(--lm); margin-bottom: 1rem;">🏆</div>
            <h3 style="color: var(--navy); margin-bottom: 0.5rem; font-family: 'DM Sans', sans-serif;">No completed tasks yet.</h3>
            <p style="color: var(--muted-text); font-size: 0.95rem;">Keep going! Your completed tasks will appear here.</p>
        </div>
    @else
        <div style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));">
            @foreach($tasks as $task)
                <div class="ui-card" style="padding: 1.5rem; display: flex; flex-direction: column; position: relative;">
                    <div style="position: absolute; top: 1.5rem; right: 1.5rem; z-index: 10;">
                        <input type="checkbox" class="task-checkbox" value="{{ $task['id'] }}" onchange="updateSelectedCount()" style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--lav);">
                    </div>
                    
                    <div style="padding-right: 2rem;">
                        <h3 style="font-size: 1.1rem; color: var(--muted-text); margin-bottom: 0.5rem; text-decoration: line-through;">
                            {{ $task['title'] }}
                        </h3>
                        
                        @if($task['description'])
                            <p style="font-size: 0.85rem; color: var(--muted-text); margin-bottom: 1rem; line-height: 1.5; opacity: 0.8;">
                                {{ Str::limit($task['description'], 100) }}
                            </p>
                        @endif
                        
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.25rem;">
                            <span class="ui-tag ui-tag-status-completed">Completed</span>
                            
                            @if($task['priority'] === 'high')
                                <span class="ui-tag ui-tag-priority-high">High</span>
                            @elseif($task['priority'] === 'medium')
                                <span class="ui-tag ui-tag-priority-medium">Medium</span>
                            @else
                                <span class="ui-tag ui-tag-priority-low">Low</span>
                            @endif
                            
                            <span class="ui-tag" style="background: var(--surface-field); color: var(--muted-text);">
                                Done: {{ \Carbon\Carbon::parse($task['updated_at'])->format('M j, Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <div style="margin-top: auto; border-top: 1px solid var(--lm); padding-top: 1rem; display: flex; justify-content: flex-end;">
                        <form action="{{ route('tasks.destroy', $task['id']) }}" method="POST" onsubmit="return confirm('Delete this completed task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; display: flex; align-items: center; gap: 0.3rem; font-size: 0.8rem; font-weight: 600; opacity: 0.8; transition: opacity 0.2s;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <script>
        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.task-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
            });
            
            updateSelectedCount();
        }
        
        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('.task-checkbox');
            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            const deleteBtn = document.getElementById('deleteSelectedBtn');
            const selectAllBtn = document.getElementById('selectAllBtn');
            
            if (deleteBtn) {
                deleteBtn.disabled = checkedCount === 0;
                deleteBtn.textContent = checkedCount > 0 ? `Delete Selected (${checkedCount})` : 'Delete Selected';
            }
            
            if (selectAllBtn && checkboxes.length > 0) {
                selectAllBtn.textContent = checkedCount === checkboxes.length ? 'Deselect All' : 'Select All';
            }
        }
        
        function submitSelected() {
            const checkedBoxes = document.querySelectorAll('.task-checkbox:checked');
            if (checkedBoxes.length === 0) return;
            
            if (!confirm(`Are you sure you want to delete ${checkedBoxes.length} selected tasks?`)) {
                return;
            }
            
            const container = document.getElementById('hiddenCheckboxes');
            container.innerHTML = '';
            
            checkedBoxes.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'task_ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
            
            document.getElementById('deleteSelectedForm').submit();
        }
    </script>
</x-layout>
