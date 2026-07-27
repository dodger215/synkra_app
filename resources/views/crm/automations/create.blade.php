<x-layouts.app title="Create Automation">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 700px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('crm.automations.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                <i class="fa-solid fa-arrow-left"></i> Back to Automations
            </a>
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Create Automation Trigger</h1>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
            <form action="{{ route('crm.automations.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <x-ui.input name="name" label="Automation Name" required placeholder="e.g. Welcome New Customers" />
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">When this happens (Event)</label>
                        <select name="event_type" required style="width: 100%; padding: 0.75rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary);">
                            <option value="customer_created">New Customer Registered</option>
                            <option value="order_placed">Purchase Completed</option>
                            <option value="points_earned">Loyalty Points Earned</option>
                            <option value="tier_upgraded">Customer Tier Upgraded</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">Do this (Action)</label>
                        <select name="action_type" required style="width: 100%; padding: 0.75rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary);">
                            <option value="send_email">Send Email</option>
                            <option value="send_sms">Send SMS</option>
                            <option value="add_to_segment">Move to Segment</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 2rem; padding: 1.5rem; background: rgba(59, 130, 246, 0.05); border-radius: 16px; border: 1px dashed var(--primary);">
                    <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;"><strong>Action Configuration</strong></p>
                    <textarea name="action_config[message]" rows="3" style="width: 100%; border-radius: 12px; border: 1px solid var(--border); background: var(--surface); color: var(--text-primary); padding: 0.75rem; font-family: inherit;" placeholder="Message body for automation..."></textarea>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="{{ route('crm.automations.index') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none;">Cancel</a>
                    <button type="submit" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer;">
                        Create Automation
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
