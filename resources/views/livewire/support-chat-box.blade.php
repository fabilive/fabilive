<div style="background: #111; padding: 20px; border-radius: 12px; border: 1px solid #333; margin-top: 10px;">
    @if (session()->has('message'))
        <div style="background: #28a745; color: #fff; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 14px;">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="sendReply">
        <textarea 
            wire:model="message" 
            placeholder="Type your reply here..." 
            style="width: 100%; min-height: 80px; padding: 12px; background: #222; color: #fff; border: 1px solid #444; border-radius: 8px; font-size: 14px; resize: vertical; margin-bottom: 10px; outline: none;"
        ></textarea>
        
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
            <input 
                type="file" 
                wire:model="attachment" 
                style="font-size: 12px; color: #aaa; background: transparent;"
            >
            
            <button 
                type="submit" 
                style="padding: 10px 24px; background: #007bff; color: #fff; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.2s;"
                onmouseover="this.style.background='#0056b3'"
                onmouseout="this.style.background='#007bff'"
            >
                Send Reply
            </button>
        </div>
        
        <div wire:loading wire:target="attachment" style="font-size: 12px; color: #ffd700; margin-top: 5px;">
            Uploading attachment...
        </div>
        <div wire:loading wire:target="sendReply" style="font-size: 12px; color: #ffd700; margin-top: 5px;">
            Sending message...
        </div>
    </form>
</div>
