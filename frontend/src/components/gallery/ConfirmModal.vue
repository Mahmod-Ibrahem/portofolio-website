<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="open" class="confirm-modal-overlay" @click.self="$emit('cancel')">
                <div class="confirm-modal-card">
                    <!-- Icon -->
                    <div class="confirm-modal-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>

                    <!-- Title -->
                    <h3 class="confirm-modal-title">{{ title }}</h3>

                    <!-- Message -->
                    <p class="confirm-modal-message">{{ message }}</p>

                    <!-- Actions -->
                    <div class="confirm-modal-actions">
                        <button class="confirm-modal-btn cancel" @click="$emit('cancel')">
                            إلغاء
                        </button>
                        <button class="confirm-modal-btn confirm" @click="$emit('confirm')">
                            تأكيد الحذف
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
defineProps({
    open: { type: Boolean, default: false },
    title: { type: String, default: 'حذف العنصر؟' },
    message: { type: String, default: 'هل أنت متأكد من أنك تريد حذف هذا العنصر؟ لا يمكن التراجع عن هذا الإجراء.' },
})

defineEmits(['confirm', 'cancel'])
</script>

<style scoped>
.confirm-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.60);
    backdrop-filter: blur(6px);
    padding: 1rem;
}

.confirm-modal-card {
    background: #1e293b;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 1.25rem;
    max-width: 400px;
    width: 100%;
    padding: 2rem;
    text-align: center;
    box-shadow:
        0 25px 50px rgba(0, 0, 0, 0.5),
        0 0 0 1px rgba(255, 255, 255, 0.05);
    animation: modalPop 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes modalPop {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(10px);
    }

    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.confirm-modal-icon {
    width: 56px;
    height: 56px;
    margin: 0 auto 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(239, 68, 68, 0.12);
    color: #f87171;
}

.confirm-modal-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #f1f5f9;
    margin-bottom: 0.5rem;
}

.confirm-modal-message {
    font-size: 0.9rem;
    color: #94a3b8;
    line-height: 1.6;
    margin-bottom: 1.75rem;
}

.confirm-modal-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
}

.confirm-modal-btn {
    flex: 1;
    padding: 0.7rem 1.25rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.confirm-modal-btn.cancel {
    background: rgba(255, 255, 255, 0.06);
    color: #cbd5e1;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.confirm-modal-btn.cancel:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #f1f5f9;
}

.confirm-modal-btn.confirm {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
}

.confirm-modal-btn.confirm:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.45);
    transform: translateY(-1px);
}

/* Transition */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
