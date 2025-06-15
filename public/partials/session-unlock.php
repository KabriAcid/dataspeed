<!-- Re-authentication Modal -->
<div id="reauthModal" class="modal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;">
    <div class="modal-dialog bg-white p-4 rounded shadow" style="max-width:350px;">
        <h5 class="mb-3">Session Locked</h5>
        <p class="text-sm mb-2">For your security, please re-enter your password to continue.</p>
        <input type="password" id="reauthPassword" class="form-control mb-2" placeholder="Password" autocomplete="current-password" />
        <button id="reauthSubmit" class="btn primary-btn w-100 mb-2">Unlock</button>
    </div>
</div>