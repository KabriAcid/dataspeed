<!-- Re-authentication Modal -->
<div id="reauthModal" class="modal">
    <div class="modal-dialog bg-white p-4 rounded shadow" style="max-width:350px;">
        <h5 class="mb-3 text-center">Session Locked</h5>
        <p class="text-sm mb-3 text-center">For your security, please re-enter your password to continue.</p>
        <input type="password" id="reauthPassword" class="input mb-2" placeholder="Password" />
        <div class="d-flex justify-content-between mt-3">
            <button id="reauthExit" class="btn btn-sm secondary-btn mb-2">Exit</button>
            <button id="reauthSubmit" class="btn primary-btn mb-2">Unlock</button>
        </div>
    </div>
</div>