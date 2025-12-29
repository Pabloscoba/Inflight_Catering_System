# INFLIGHT CATERING SYSTEM - ROLE CONTROL FLOW

## 🎯 **COMPLETE CONTROL FLOW PER ROLE**

---

## 1️⃣ **CATERING STAFF**

### **Dashboard Access**
- URL: `/catering-staff/dashboard`
- View: Personal statistics, pending requests, recent requests

### **Control Flow**

```
LOGIN (as Catering Staff)
    ↓
DASHBOARD
    ↓
┌─────────────────────────────────────────────┐
│ OPTION 1: Create New Request                │
│ OPTION 2: View My Requests                  │
│ OPTION 3: Receive Items                     │
│ OPTION 4: Create Additional Request         │
└─────────────────────────────────────────────┘

OPTION 1: CREATE NEW REQUEST
    ↓
Select Flight → Browse Products (from quantity_in_stock) → Add Items → Submit
    ↓
Status: pending_catering_incharge
    ↓
Wait for Catering Incharge Approval
    ↓
IF APPROVED: Status → catering_approved (wait for supervisor)
IF REJECTED: Status → rejected (END)

OPTION 2: VIEW MY REQUESTS
    ↓
See list of all requests:
    - Pending (yellow badge)
    - Approved (green badge)
    - Rejected (red badge)
    - Items Issued (blue badge)
    ↓
Click request → View details (flight, items, status, approvers)

OPTION 3: RECEIVE ITEMS (After items_issued status)
    ↓
Go to "Items to Receive" page
    ↓
See requests with status: items_issued
    ↓
Click "Confirm Receipt" button
    ↓
Status: items_issued → pending_final_approval
    ↓
Wait for Catering Incharge Final Approval

OPTION 4: CREATE ADDITIONAL REQUEST
    ↓
Request products not in main inventory
    ↓
Submit to Inventory Personnel
    ↓
Wait for approval and delivery
```

### **Permissions**
- ✅ `create catering requests`
- ✅ `view own catering requests`
- ✅ `receive items`

### **Key Pages**
1. Dashboard: `/catering-staff/dashboard`
2. Create Request: `/catering-staff/requests/create`
3. My Requests: `/catering-staff/requests`
4. Receive Items: `/catering-staff/requests/items-to-receive`
5. Additional Requests: `/catering-staff/additional-requests`

---

## 2️⃣ **CATERING INCHARGE**

### **Dashboard Access**
- URL: `/catering-incharge/dashboard`
- View: Stock overview, low stock alerts, pending requests, statistics

### **Control Flow**

```
LOGIN (as Catering Incharge)
    ↓
DASHBOARD
    ↓
┌──────────────────────────────────────────────┐
│ OPTION 1: Approve Initial Requests (Step 2)  │
│ OPTION 2: Give Final Approval (Step 6)       │
│ OPTION 3: Monitor Low Stock                  │
│ OPTION 4: View All Requests                  │
│ OPTION 5: Meal Approvals                     │
└──────────────────────────────────────────────┘

OPTION 1: APPROVE INITIAL REQUESTS (Step 2)
    ↓
Go to "Pending Requests" page
    ↓
See requests with status: pending_catering_incharge
    ↓
Click request → Review details (flight, items, quantities)
    ↓
DECISION:
├─ APPROVE: Status → catering_approved (forward to Supervisor)
└─ REJECT: Enter reason → Status → rejected (notify Catering Staff)

OPTION 2: GIVE FINAL APPROVAL (Step 6)
    ↓
Go to "Pending Final Approval" page
    ↓
See requests with status: pending_final_approval
    ↓
These are requests where Catering Staff already received items
    ↓
Click request → Verify items received correctly
    ↓
DECISION:
├─ FINAL APPROVE: Status → catering_final_approved (forward to Security)
└─ REJECT: Enter reason → Status → rejected

OPTION 3: MONITOR LOW STOCK
    ↓
Dashboard shows "Low Stock Alert" section
    ↓
Products where: quantity_in_stock ≤ reorder_level
    ↓
Color-coded:
├─ Red: quantity_in_stock = 0 (OUT OF STOCK)
└─ Orange: quantity_in_stock > 0 but ≤ reorder_level (LOW STOCK)
    ↓
Click "View All Stock" → See complete inventory

OPTION 4: VIEW ALL REQUESTS
    ↓
Filter by status: All, Pending, Approved, Rejected
    ↓
Search by flight number, requester
    ↓
Click request → View full details

OPTION 5: MEAL APPROVALS
    ↓
Approve/reject meal plans from Catering Staff
    ↓
Similar flow to request approvals
```

### **Permissions**
- ✅ `approve catering staff requests`
- ✅ `view all catering requests`
- ✅ `oversee catering stock`
- ✅ `receive products from inventory`

### **Key Pages**
1. Dashboard: `/catering-incharge/dashboard`
2. Pending Requests: `/catering-incharge/requests/pending`
3. Pending Final Approval: `/catering-incharge/requests/pending-final`
4. Approved Requests: `/catering-incharge/requests/approved`
5. Stock Overview: `/catering-incharge/receipts/stock-overview`
6. Meal Approvals: `/catering-incharge/meals`

---

## 3️⃣ **SUPERVISOR**

### **Dashboard Access**
- URL: `/supervisor/dashboard`
- View: Pending approvals, approved requests, budget overview

### **Control Flow**

```
LOGIN (as Supervisor)
    ↓
DASHBOARD
    ↓
┌──────────────────────────────────────────────┐
│ OPTION 1: Approve Requests (Step 3)          │
│ OPTION 2: View All Requests                  │
│ OPTION 3: Monitor Budget & Compliance        │
└──────────────────────────────────────────────┘

OPTION 1: APPROVE REQUESTS (Step 3)
    ↓
See requests with status: catering_approved
    ↓
These are requests already approved by Catering Incharge
    ↓
Click request → Review:
├─ Flight details
├─ Items and quantities
├─ Requester information
└─ Previous approvers
    ↓
DECISION:
├─ APPROVE: Status → supervisor_approved (forward to Inventory)
└─ REJECT: Enter reason → Status → rejected (notify requester)

OPTION 2: VIEW ALL REQUESTS
    ↓
Filter by:
├─ Status (All, Pending, Approved, Rejected)
├─ Date range
├─ Flight number
└─ Requester
    ↓
Export reports for analysis

OPTION 3: MONITOR BUDGET & COMPLIANCE
    ↓
View statistics:
├─ Total requests this month
├─ Approval rate
├─ Average processing time
└─ Cost analysis
```

### **Permissions**
- ✅ `approve supervisor requests`
- ✅ `view all requests`
- ✅ `monitor compliance`

### **Key Pages**
1. Dashboard: `/supervisor/dashboard`
2. Pending Approvals: `/supervisor/requests/pending`
3. All Requests: `/supervisor/requests`
4. Request Details: `/supervisor/requests/{id}`

---

## 4️⃣ **INVENTORY PERSONNEL**

### **Dashboard Access**
- URL: `/inventory/dashboard`
- View: Pending issues, stock levels, movement history

### **Control Flow**

```
LOGIN (as Inventory Personnel)
    ↓
DASHBOARD
    ↓
┌──────────────────────────────────────────────┐
│ OPTION 1: Issue Items (Step 4)               │
│ OPTION 2: Manage Stock                       │
│ OPTION 3: Additional Requests                │
│ OPTION 4: View Stock Movements               │
└──────────────────────────────────────────────┘

OPTION 1: ISSUE ITEMS (Step 4)
    ↓
See requests with status: supervisor_approved
    ↓
Click request → See items list
    ↓
For each item:
├─ Check quantity_in_stock availability
├─ Verify product location in warehouse
└─ Prepare items for pickup
    ↓
Click "Issue Items" button
    ↓
System automatically:
├─ Deducts from quantity_in_stock
├─ Creates stock_movements record
├─ Updates request status → items_issued
└─ Notifies Catering Staff (items ready for pickup)

OPTION 2: MANAGE STOCK
    ↓
Add new products
Update stock quantities (quantity_in_stock)
Set reorder levels
Manage categories

OPTION 3: ADDITIONAL REQUESTS
    ↓
See additional product requests from Catering Staff
    ↓
DECISION:
├─ APPROVE: Procure product → Add to inventory → Deliver
└─ REJECT: Enter reason (product unavailable, budget, etc.)

OPTION 4: VIEW STOCK MOVEMENTS
    ↓
See all stock transactions:
├─ Issues to Catering Staff
├─ Receipts from suppliers
├─ Returns
└─ Adjustments
```

### **Permissions**
- ✅ `manage inventory`
- ✅ `issue products`
- ✅ `view stock movements`
- ✅ `approve additional requests`

### **Key Pages**
1. Dashboard: `/inventory/dashboard`
2. Issue Items: `/inventory/requests/pending-issue`
3. Stock Management: `/inventory/products`
4. Stock Movements: `/inventory/movements`
5. Additional Requests: `/inventory/additional-requests`

---

## 5️⃣ **SECURITY STAFF**

### **Dashboard Access**
- URL: `/security-staff/dashboard`
- View: Risk summary, pending authentication, recent verifications

### **Control Flow**

```
LOGIN (as Security Staff)
    ↓
DASHBOARD - See Verification Summary
    ↓
┌────────────────────────────────────────────────┐
│ Risk Breakdown Cards:                          │
│ 🔴 HIGH RISK: X requests (score ≥ 3)          │
│ 🟠 MEDIUM RISK: X requests (score ≥ 1)        │
│ 🟢 LOW RISK: X requests (score < 1)           │
│ ⏰ URGENT: X requests (< 6 hours to departure)│
└────────────────────────────────────────────────┘
    ↓
┌──────────────────────────────────────────────┐
│ OPTION 1: Authenticate Requests (Step 7)     │
│ OPTION 2: View Recent Verifications          │
└──────────────────────────────────────────────┘

OPTION 1: AUTHENTICATE REQUESTS (Step 7)
    ↓
Click "View Awaiting Authentication" or click on risk card
    ↓
See requests with status: catering_final_approved
    ↓
Each request shows:
├─ Risk Level Badge (🔴 HIGH / 🟠 MEDIUM / 🟢 LOW)
├─ Urgency Indicator (⏰ if < 6 hours)
├─ Document Checks Score (e.g., "Checks: 5/5 ✓")
├─ Aircraft Type, Flight Number
└─ Departure Time
    ↓
Click "Verify & Authenticate" → Open detailed verification page
    ↓
VERIFICATION CHECKLIST:
    ↓
1. DOCUMENT ASSESSMENT:
   ✓ Flight Number exists?
   ✓ Aircraft Type valid?
   ✓ Requester authorized?
   ✓ Within cutoff time (departure > 2 hours)?
   ✓ Status valid (catering_final_approved)?
    ↓
2. RISK ASSESSMENT (Auto-calculated):
   Score = 0
   IF departure < 6 hours: +2 points
   IF items count > 20: +1 point
   IF total quantity > 100: +1 point
   
   Risk Level:
   ├─ Score ≥ 3: 🔴 HIGH RISK (red badge)
   ├─ Score ≥ 1: 🟠 MEDIUM RISK (orange badge)
   └─ Score < 1: 🟢 LOW RISK (green badge)
    ↓
3. COMPLIANCE ASSESSMENT:
   ✓ All items have category?
   ✓ High quantity items (> 50 units) justified?
   ✓ Total items = X, items with category = X
    ↓
4. INTEGRITY ASSESSMENT:
   ✓ Items match flight requirements?
   ✓ Quantities reasonable?
   ✓ No suspicious patterns?
    ↓
DECISION:
├─ AUTHENTICATE: Status → security_authenticated (forward to Ramp)
│   System notifies: Ramp Agent
│
└─ REJECT: Enter security reason → Status → rejected
    System notifies: Catering Incharge, Requester

OPTION 2: VIEW RECENT VERIFICATIONS
    ↓
"Recent Stock Movements" section shows:
├─ ✓ security_authenticated (verified recently)
├─ ✈️ ramp_dispatched (dispatched to aircraft)
├─ 📦 loaded (loaded on aircraft)
├─ ✅ delivered (delivered to cabin)
└─ 🍽️ served (service completed)
    ↓
Click request → View full verification history
```

### **Permissions**
- ✅ `authenticate catering requests`
- ✅ `view security logs`
- ✅ `reject suspicious requests`

### **Key Pages**
1. Dashboard: `/security-staff/dashboard`
2. Awaiting Authentication: `/security-staff/requests/awaiting-authentication`
3. Verify Request: `/security-staff/requests/{id}`
4. Authenticate Action: `/security-staff/requests/{id}/authenticate`
5. Recent Movements: Shown on dashboard

### **Automated Features**
- **Risk Scoring Algorithm**: Automatic calculation based on urgency + complexity
- **Document Validation**: 5 automated checks
- **Items Metrics**: Auto-count total items, categorized items, high-quantity items
- **Visual Indicators**: Color-coded badges, urgency flags, verification scores

---

## 6️⃣ **RAMP AGENT (DISPATCHER)**

### **Dashboard Access**
- URL: `/ramp-dispatcher/dashboard`
- View: Authenticated requests ready for dispatch

### **Control Flow**

```
LOGIN (as Ramp Agent)
    ↓
DASHBOARD
    ↓
┌──────────────────────────────────────────────┐
│ OPTION 1: Dispatch to Aircraft (Step 8)      │
│ OPTION 2: View Dispatch History              │
└──────────────────────────────────────────────┘

OPTION 1: DISPATCH TO AIRCRAFT (Step 8)
    ↓
See requests with status: security_authenticated
    ↓
Each request shows:
├─ Flight Number & Aircraft Type
├─ Departure Time
├─ Items list
└─ Gate/Ramp location
    ↓
Click request → View full details
    ↓
Coordinate with ground crew
Prepare items for aircraft delivery
    ↓
Click "Dispatch" button
    ↓
Status: security_authenticated → ramp_dispatched
    ↓
System notifies: Flight Purser (items on the way)

OPTION 2: VIEW DISPATCH HISTORY
    ↓
See all dispatched requests
Filter by:
├─ Date
├─ Flight number
└─ Aircraft type
```

### **Permissions**
- ✅ `dispatch to aircraft`
- ✅ `view authenticated requests`
- ✅ `coordinate with flight crew`

### **Key Pages**
1. Dashboard: `/ramp-dispatcher/dashboard`
2. Dispatch Action: `/ramp-dispatcher/requests/{id}/dispatch`
3. Dispatch History: `/ramp-dispatcher/requests/dispatched`

---

## 7️⃣ **FLIGHT PURSER**

### **Dashboard Access**
- URL: `/flight-purser/dashboard`
- View: Dispatched items, loading status

### **Control Flow**

```
LOGIN (as Flight Purser)
    ↓
DASHBOARD
    ↓
┌──────────────────────────────────────────────┐
│ OPTION 1: Load Items to Aircraft (Step 9)    │
│ OPTION 2: Confirm Delivery to Cabin (Step 10)│
│ OPTION 3: View Flight Assignments            │
└──────────────────────────────────────────────┘

OPTION 1: LOAD ITEMS TO AIRCRAFT (Step 9)
    ↓
See requests with status: ramp_dispatched
    ↓
Items delivered to aircraft ramp
    ↓
Click request → Verify items list
    ↓
Physically inspect and count items
    ↓
Click "Confirm Loading" button
    ↓
Status: ramp_dispatched → loaded
    ↓
System notifies: Flight Purser (for delivery confirmation)

OPTION 2: CONFIRM DELIVERY TO CABIN (Step 10)
    ↓
See requests with status: loaded
    ↓
Items now on aircraft, ready for cabin delivery
    ↓
Click request → Confirm items moved to cabin storage
    ↓
Click "Confirm Delivery" button
    ↓
Status: loaded → delivered
    ↓
System notifies: Cabin Crew (items ready for service)

OPTION 3: VIEW FLIGHT ASSIGNMENTS
    ↓
See all flights assigned to you
Filter by:
├─ Date
├─ Status (loading, loaded, delivered)
└─ Aircraft type
```

### **Permissions**
- ✅ `load aircraft items`
- ✅ `confirm delivery to cabin`
- ✅ `view flight assignments`

### **Key Pages**
1. Dashboard: `/flight-purser/dashboard`
2. Load Action: `/flight-purser/requests/{id}/load`
3. Delivery Confirmation: `/flight-purser/requests/{id}/deliver`
4. Flight Assignments: `/flight-purser/flights`

---

## 8️⃣ **CABIN CREW**

### **Dashboard Access**
- URL: `/cabin-crew/dashboard`
- View: Delivered items, service status

### **Control Flow**

```
LOGIN (as Cabin Crew)
    ↓
DASHBOARD
    ↓
┌──────────────────────────────────────────────┐
│ OPTION 1: Record Service Completion (Step 11)│
│ OPTION 2: View Usage History                 │
│ OPTION 3: Report Issues/Returns               │
└──────────────────────────────────────────────┘

OPTION 1: RECORD SERVICE COMPLETION (Step 11)
    ↓
See requests with status: delivered
    ↓
Items ready for passenger service
    ↓
Click "Record Usage" → Open consumption form
    ↓
FILL CONSUMPTION FORM:
├─ Meals Served: [Number] (e.g., 150 meals)
├─ Beverages Served: [Number] (e.g., 200 drinks)
├─ Snacks Served: [Number] (e.g., 80 snacks)
├─ Items Not Used: [List] (e.g., 10 beef meals, 5 cokes)
└─ Notes: [Optional feedback]
    ↓
Click "Submit Service Report"
    ↓
Status: delivered → served (WORKFLOW COMPLETE ✓)
    ↓
System records:
├─ Actual consumption data
├─ Unused items (for return/analysis)
├─ Service completion time
└─ Crew member who submitted

OPTION 2: VIEW USAGE HISTORY
    ↓
See all completed services
Filter by:
├─ Date
├─ Flight number
└─ Service type

OPTION 3: REPORT ISSUES/RETURNS
    ↓
Report:
├─ Damaged items
├─ Quality issues
├─ Shortages
└─ Excess quantities
```

### **Permissions**
- ✅ `record service completion`
- ✅ `submit usage reports`
- ✅ `view assigned flights`
- ✅ `report item issues`

### **Key Pages**
1. Dashboard: `/cabin-crew/dashboard`
2. Record Usage: `/cabin-crew/requests/{id}/served-form`
3. Submit Service: `/cabin-crew/requests/{id}/served` (POST)
4. Usage History: `/cabin-crew/usage`
5. Issue Reports: `/cabin-crew/issues`

---

## 9️⃣ **ADMIN**

### **Dashboard Access**
- URL: `/admin/dashboard`
- View: System overview, all activities

### **Control Flow**

```
LOGIN (as Admin)
    ↓
ADMIN DASHBOARD
    ↓
┌──────────────────────────────────────────────┐
│ FULL SYSTEM CONTROL                          │
├──────────────────────────────────────────────┤
│ 1. User Management                           │
│ 2. Role & Permission Management              │
│ 3. Product Management                        │
│ 4. Category Management                       │
│ 5. Flight Management                         │
│ 6. Request Monitoring                        │
│ 7. Stock Overview                            │
│ 8. System Settings                           │
│ 9. Reports & Analytics                       │
│ 10. Activity Logs                            │
└──────────────────────────────────────────────┘

1. USER MANAGEMENT:
   ├─ Create new users
   ├─ Assign roles (Catering Staff, Incharge, etc.)
   ├─ Activate/deactivate accounts
   └─ Reset passwords

2. ROLE & PERMISSION MANAGEMENT:
   ├─ Create custom roles
   ├─ Assign permissions to roles
   ├─ Manage permission groups
   └─ View permission matrix

3. PRODUCT MANAGEMENT:
   ├─ Add/edit/delete products
   ├─ Set stock levels (quantity_in_stock)
   ├─ Set reorder levels
   ├─ Manage product categories
   └─ Activate/deactivate products

4. FLIGHT MANAGEMENT:
   ├─ View all flights
   ├─ Monitor flight-request relationships
   └─ Flight statistics

5. REQUEST MONITORING:
   ├─ View ALL requests (any status)
   ├─ Override approvals (emergency)
   ├─ Reset request status
   └─ Delete test/invalid requests

6. SYSTEM SETTINGS:
   ├─ Configure cutoff times
   ├─ Set notification preferences
   ├─ Manage system parameters
   └─ Configure security thresholds

7. REPORTS & ANALYTICS:
   ├─ Usage reports (consumption analysis)
   ├─ Approval time analysis
   ├─ Stock turnover reports
   ├─ User activity reports
   └─ Export to Excel/PDF

8. ACTIVITY LOGS:
   ├─ View all system activities
   ├─ Filter by user, action, date
   ├─ Audit trail for compliance
   └─ Security incident logs
```

### **Permissions**
- ✅ ALL PERMISSIONS (Super Admin)

### **Key Pages**
1. Dashboard: `/admin/dashboard`
2. Users: `/admin/users`
3. Roles: `/admin/roles`
4. Products: `/admin/products`
5. Requests: `/admin/requests`
6. Reports: `/admin/reports`
7. Settings: `/admin/settings`
8. Logs: `/admin/logs`

---

## 📊 **PERMISSION MATRIX**

| Permission | Catering Staff | Incharge | Supervisor | Inventory | Security | Ramp | Purser | Cabin | Admin |
|-----------|---------------|----------|------------|-----------|----------|------|--------|-------|-------|
| Create requests | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| Approve initial | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| Supervisor approve | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| Issue items | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ✅ |
| Receive items | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| Final approve | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| Authenticate | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ✅ |
| Dispatch | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ✅ |
| Load aircraft | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ✅ |
| Deliver to cabin | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ✅ |
| Record service | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| View all requests | ❌ | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ |
| Manage stock | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ✅ |
| System settings | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |

---

## 🔄 **STATUS TRANSITIONS BY ROLE**

```
pending_catering_incharge → catering_approved
    👤 Catering Incharge (approve)

catering_approved → supervisor_approved
    👤 Supervisor (approve)

supervisor_approved → items_issued
    👤 Inventory Personnel (issue items)

items_issued → pending_final_approval
    👤 Catering Staff (receive items)

pending_final_approval → catering_final_approved
    👤 Catering Incharge (final approve)

catering_final_approved → security_authenticated
    👤 Security Staff (authenticate)

security_authenticated → ramp_dispatched
    👤 Ramp Agent (dispatch)

ramp_dispatched → loaded
    👤 Flight Purser (load)

loaded → delivered
    👤 Flight Purser (deliver)

delivered → served
    👤 Cabin Crew (record service)

ANY STATUS → rejected
    👤 Catering Incharge, Supervisor, or Security (reject)
```

---

## 🎯 **TYPICAL USER JOURNEYS**

### **Journey 1: Successful Request (Happy Path)**
1. **Catering Staff**: Create request → pending_catering_incharge
2. **Catering Incharge**: Approve → catering_approved
3. **Supervisor**: Approve → supervisor_approved
4. **Inventory**: Issue items → items_issued
5. **Catering Staff**: Receive items → pending_final_approval
6. **Catering Incharge**: Final approve → catering_final_approved
7. **Security**: Authenticate → security_authenticated
8. **Ramp Agent**: Dispatch → ramp_dispatched
9. **Flight Purser**: Load → loaded → delivered
10. **Cabin Crew**: Record service → served ✅

### **Journey 2: Rejected Request**
1. **Catering Staff**: Create request → pending_catering_incharge
2. **Catering Incharge**: Reject (reason: "Insufficient budget") → rejected ❌
3. **Catering Staff**: Receives notification with rejection reason

### **Journey 3: Security Rejection**
1-6. **Normal flow** → catering_final_approved
7. **Security**: Reject (reason: "Suspicious quantity pattern") → rejected ❌
8. **All parties**: Receive security alert notification

---

**Total Roles**: 9 (including Admin)  
**Total Workflow Steps**: 11  
**Total Statuses**: 12 (including rejected)  
**Average Processing Time**: 2-4 hours (from creation to authentication)
