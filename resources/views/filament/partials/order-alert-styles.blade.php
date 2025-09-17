<style>
/* Order alert styles (subtle, professional) – row-based */
tr.order-alert-row {
  background-color: rgba(239, 68, 68, 0.04) !important; /* red-500 @ 4% */
}
tr.order-alert-row > td:first-child {
  position: relative;
}
tr.order-alert-row > td:first-child::before {
  content: "";
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 3px;
  background-color: #ef4444; /* red-500 */
  border-radius: 2px;
}

/* Subtle red ring for inputs in the alert row */
tr.order-alert-row .fi-input-wrp {
  background-color: transparent !important;
  box-shadow: inset 0 0 0 1px rgba(239, 68, 68, 0.35) !important; /* red ring */
}
tr.order-alert-row .fi-input-wrp:focus-within {
  box-shadow: inset 0 0 0 2px rgba(239, 68, 68, 0.55) !important;
}

/* Input placeholder hint only (keep text color normal for readability) */
.order-alert-input::placeholder {
  color: #fca5a5 !important; /* red-300 */
}

@media (prefers-color-scheme: dark) {
  tr.order-alert-row { background-color: rgba(239, 68, 68, 0.06) !important; }
  tr.order-alert-row .fi-input-wrp { box-shadow: inset 0 0 0 1px rgba(248, 113, 113, 0.35) !important; }
  tr.order-alert-row .fi-input-wrp:focus-within { box-shadow: inset 0 0 0 2px rgba(248, 113, 113, 0.6) !important; }
}
</style>
