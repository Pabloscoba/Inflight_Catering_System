# Catering Mini Stock System - Quick Start Guide

## What Was Implemented?

The system now has **two separate inventory levels**:
1. **Main Warehouse** (managed by Inventory Personnel)
2. **Catering Mini Stock** (used by Catering Staff)

Products must be **transferred** from main warehouse to catering mini stock before Catering Staff can request them.

---

## For Inventory Personnel

### How to Transfer Stock to Catering Department

1. **Navigate to Stock Movements**
   - Go to: Inventory Personnel Dashboard → Stock Movements
   
2. **Click "🔄 Transfer to Catering" button**
   - Located at the top with other action buttons (Incoming, Issue, Returns)

3. **Fill Transfer Form**
   - **Product**: Select from dropdown (only shows products with available main stock)
   - **Quantity**: Enter amount to transfer
   - **Reference Number**: Auto-generated (e.g., TRF-CAT-20250127-001)
   - **Transfer Date**: Today's date (can be changed)
   - **Notes**: Optional explanation (e.g., "Weekly catering stock replenishment")

4. **Submit Request**
   - Transfer is created with status "pending"
   - Main stock NOT moved yet (awaits supervisor approval)
   - You'll see confirmation: "Transfer to catering recorded and pending supervisor approval"

### View Your Transfer Requests
- Go to Stock Movements index
- Filter by Type: "Transfer to Catering"
- Status will show as "pending" until approved

---

## For Inventory Supervisor

### How to Approve Transfers

1. **Navigate to Pending Approvals**
   - Dashboard shows count of "Pending Movements"
   - Click on "Pending Stock Movements" or go to Approvals → Stock Movements

2. **Review Transfer Requests**
   - Look for movements with **blue "Transfer to Catering" badge**
   - Check:
     * Product name
     * Quantity being transferred
     * Who requested it
     * Reference number and notes

3. **Approve or Reject**
   - **Approve**: Main stock decreases, Catering stock increases automatically
   - **Reject**: No stock movement occurs

### What Happens When You Approve?
```
Before: Main Stock = 410, Catering Stock = 0
Transfer Request: 50 units
After Approval: Main Stock = 360, Catering Stock = 50
```

The system does this **atomically** (both changes happen together, cannot fail halfway).

---

## For Catering Staff

### What Changed?

**BEFORE**: You could request any product from main inventory
**NOW**: You can only request products available in your catering mini stock

### How to See Available Stock

1. **Go to Create Request**
2. **Product Dropdown** now shows ONLY products with:
   - `catering_stock > 0`
   
3. If you don't see a product you need:
   - Ask Inventory Personnel to transfer it from main warehouse
   - They submit transfer → Supervisor approves → Product appears in your list

### Example Scenario
```
Product: Chicken Meal
Main Inventory: 410 units
Catering Stock: 0 units

❌ You CANNOT request Chicken Meal yet

After Transfer (50 units approved):
Main Inventory: 360 units  
Catering Stock: 50 units

✅ You CAN NOW request Chicken Meal (up to 50 units)
```

---

## Dashboard & Monitoring

### Test Script Available
Run this command to check stock status:
```bash
php test-catering-stock.php
```

**Shows**:
- Product stock levels (Main vs Catering)
- Pending transfer requests
- Approved transfers
- Products available to Catering Staff
- Stock distribution statistics

### Key Metrics
- **Catering Stock Low**: When `catering_stock ≤ catering_reorder_level` (default 10)
- **Catering Stock Out**: When `catering_stock = 0`

---

## Transfer Workflow Summary

```
┌─────────────────────┐
│ INVENTORY PERSONNEL │
│  (Create Transfer)  │
└──────────┬──────────┘
           │ Submit Transfer Request
           │ Status: pending
           ▼
┌─────────────────────┐
│ INVENTORY SUPERVISOR│
│  (Review & Approve) │
└──────────┬──────────┘
           │ Approve
           │ Main Stock ↓ 
           │ Catering Stock ↑
           ▼
┌─────────────────────┐
│   CATERING STAFF    │
│ (See Product in     │
│  Request Dropdown)  │
└─────────────────────┘
```

---

## Permissions Required

| Role                  | Permission Needed | Action                |
|-----------------------|-------------------|-----------------------|
| Inventory Personnel   | `add stock`       | Create transfer       |
| Inventory Supervisor  | (existing)        | Approve/Reject        |
| Catering Staff        | (existing)        | Create requests       |

No new permissions needed! Transfer uses same permission as "Incoming Stock".

---

## Important Notes

✅ **Transfers are reversible**: If needed, Catering can return stock (use Returns process)

✅ **Stock cannot go negative**: System validates main inventory has enough before creating transfer

✅ **Approval required**: Stock doesn't move until Supervisor approves

✅ **Audit trail**: All transfers logged with user, date, and reference number

⚠️ **Catering Staff limitation**: They can ONLY see their mini stock, not main inventory

---

## Troubleshooting

### "No products available in catering mini stock"
**Solution**: Inventory Personnel needs to transfer stock first

### "Insufficient stock in main inventory"
**Solution**: Add incoming stock to main warehouse first, then transfer

### Product not showing in Catering Staff dropdown
**Solution**: 
1. Check if transfer was submitted (Inventory Personnel)
2. Check if transfer was approved (Inventory Supervisor)
3. Run `php test-catering-stock.php` to verify catering_stock > 0

### Transfer request disappeared
**Solution**: Check Inventory Supervisor approvals - may have been rejected

---

## Quick Reference

### URLs (when logged in as respective role)

**Inventory Personnel**:
- Stock Movements: `/inventory-personnel/stock-movements`
- Transfer Form: `/inventory-personnel/stock-movements/transfer-to-catering`

**Inventory Supervisor**:
- Pending Approvals: `/inventory-supervisor/approvals/movements`

**Catering Staff**:
- Create Request: `/catering-staff/requests/create`

---

## Database Fields Added

| Table    | Column                  | Type    | Default | Description                |
|----------|-------------------------|---------|---------|----------------------------|
| products | catering_stock          | integer | 0       | Quantity in catering stock |
| products | catering_reorder_level  | integer | 10      | Minimum safe level         |

---

**Status**: ✅ Complete and Ready to Use
**Testing**: Run `php test-catering-stock.php` to verify system
**Documentation**: See `CATERING-MINI-STOCK.md` for technical details
