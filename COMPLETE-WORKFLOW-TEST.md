# 🔄 COMPLETE WORKFLOW TEST GUIDE
## Full System Flow: Inventory Personnel → Cabin Crew

Mwongozo wa kufuata workflow nzima ya system kuanzia Inventory Personnel mpaka Cabin Crew.

---

## 📋 WORKFLOW PATHS

System ina workflow paths mbili kuu:

### PATH 1: PRODUCT/MEAL APPROVAL WORKFLOW
**Inventory Personnel → Inventory Supervisor → Catering Incharge**

### PATH 2: REQUEST FULFILLMENT WORKFLOW  
**Catering Staff → Inventory Personnel → Inventory Supervisor → Security → Catering Incharge → Ramp Agent → Cabin Crew**

---

## 🔵 PATH 1: PRODUCT/MEAL CREATION & APPROVAL

### Step 1️⃣: **INVENTORY PERSONNEL** - Create Product
- **Login as:** Inventory Personnel
- **Navigate to:** Products → Add New Product
- **Action:** Create new product with all details
- **Result:** Product status = `pending`
- **Database:** `products.status = 'pending'`

### Step 2️⃣: **INVENTORY SUPERVISOR** - Approve Product
- **Login as:** Inventory Supervisor  
- **Navigate to:** Approvals → Pending Products
- **Action:** Review & Approve product
- **Result:** Product status = `approved`
- **Database:** `products.status = 'approved'`, `approved_by`, `approved_at` set

### Step 3️⃣: **CATERING STAFF** - Create Meal
- **Login as:** Catering Staff
- **Navigate to:** Meals → Add New Meal
- **Action:** Create new meal (with meal_type)
- **Result:** Meal status = `pending`
- **Database:** `products.status = 'pending'`, `meal_type` set

### Step 4️⃣: **CATERING INCHARGE** - Approve Meal
- **Login as:** Catering Incharge
- **Navigate to:** Meal Approvals → Pending Meals
- **Action:** Review & Approve meal
- **Result:** Meal status = `approved`
- **Database:** `products.status = 'approved'`, `approved_by`, `approved_at` set

**✅ PATH 1 COMPLETE: Products/Meals are now approved and available**

---

## 🟢 PATH 2: REQUEST FULFILLMENT WORKFLOW

### Step 1️⃣: **CATERING STAFF** - Create Request
- **Login as:** Catering Staff
- **Navigate to:** Requests → Create New Request
- **Action:** 
  - Select Flight
  - Add products/meals with quantities
  - Submit request
- **Result:** Request status = `pending_inventory`
- **Database:** `requests.status = 'pending_inventory'`, `requester_id` set
- **Verification:**
  - Check Dashboard → Pending Requests count increases
  - Check Requests page → See new request with "⏳ Pending Inventory" badge

### Step 2️⃣: **INVENTORY PERSONNEL** - Review & Forward
- **Login as:** Inventory Personnel
- **Navigate to:** Requests → Pending Requests
- **Action:** 
  - View request details
  - Click "Forward to Supervisor"
- **Result:** Request status = `pending_supervisor`
- **Database:** `requests.status = 'pending_supervisor'`
- **Verification:**
  - Request disappears from Inventory Personnel pending list
  - Check Catering Staff requests → Status shows "Pending Supervisor"

### Step 3️⃣: **INVENTORY SUPERVISOR** - Review & Approve
- **Login as:** Inventory Supervisor
- **Navigate to:** Approvals → Pending Requests
- **Action:**
  - Review request details
  - Adjust quantities if needed
  - Click "Approve & Send to Security"
- **Result:** Request status = `sent_to_security`
- **Database:** 
  - `requests.status = 'sent_to_security'`
  - `request_items.quantity_approved` set
- **Verification:**
  - Request disappears from Supervisor pending list
  - Check Catering Staff requests → Status shows "Sent to Security"

### Step 4️⃣: **SECURITY** - Authenticate Request
- **Login as:** Security Staff
- **Navigate to:** Security → Pending Authentications
- **Action:**
  - Verify request authenticity
  - Click "Authenticate & Send to Catering Incharge"
- **Result:** Request status = `security_approved`
- **Database:**
  - `requests.status = 'security_approved'`
  - `stock_movements` created with type = 'issued'
  - `products.quantity_in_stock` decremented
- **Verification:**
  - Stock deducted from main inventory
  - Request disappears from Security pending list
  - Check Catering Staff requests → Status shows "Security Approved"

### Step 5️⃣: **CATERING INCHARGE** - Approve Receipt
- **Login as:** Catering Incharge
- **Navigate to:** Requests → Pending Approvals
- **Action:**
  - Verify received products from Security
  - Click "Approve Request"
- **Result:** Request status = `catering_approved`
- **Database:**
  - `requests.status = 'catering_approved'`
  - `catering_stock` entries created
  - `catering_stock.status = 'approved'`
  - `catering_stock.quantity_available` set
- **Verification:**
  - Stock now visible in Catering Stock
  - Request shows in Catering Staff "Approved Requests"
  - Check Catering Incharge Dashboard → Approved Requests count increases

### Step 6️⃣: **CATERING STAFF** - View Approved & Send to Ramp
- **Login as:** Catering Staff
- **Navigate to:** Requests → Approved (filter)
- **Action:**
  - View approved request
  - Click "Send to Ramp Agent"
- **Result:** Request status = `sent_to_ramp`
- **Database:** `requests.status = 'sent_to_ramp'`
- **Verification:**
  - Request disappears from Catering Staff approved list
  - Check Ramp Agent dashboard → New request appears

### Step 7️⃣: **RAMP AGENT** - Dispatch to Aircraft
- **Login as:** Ramp Agent
- **Navigate to:** Dispatch → Pending Dispatch
- **Action:**
  - Verify request for flight
  - Click "Mark as Dispatched"
- **Result:** Request status = `dispatched`
- **Database:** 
  - `requests.status = 'dispatched'`
  - `dispatched_by`, `dispatched_at` set
- **Verification:**
  - Request shows as dispatched
  - Cabin Crew can now see the request

### Step 8️⃣: **FLIGHT PURSER** - Load onto Aircraft
- **Login as:** Flight Purser
- **Navigate to:** Dashboard → Requests to Load
- **Action:**
  - Click "👁️ View Products" to review items
  - Click "📦 Load onto Aircraft"
  - Confirm loading
- **Result:** Request status = `loaded`
- **Database:**
  - `requests.status = 'loaded'`
  - `loaded_by`, `loaded_at` set
- **Verification:**
  - Request shows as loaded
  - Items now on aircraft
  - Visible to Cabin Crew

### Step 9️⃣: **CABIN CREW** - Serve to Customers
- **Login as:** Cabin Crew
- **Navigate to:** Dashboard → Supplies Loaded onto Aircraft
- **Action:**
  - Click "👁️ View Items" to see all products/meals
  - Serve items to passengers during flight
  - Click "🍽️ Served to Customers"
  - Confirm service completion
- **Result:** Request status = `delivered`
- **Database:**
  - `requests.status = 'delivered'`
  - `delivered_by`, `delivered_at` set
- **Verification:**
  - Request marked as delivered
  - Service completed successfully
  - Flight service cycle complete

**✅ PATH 2 COMPLETE: Request fulfilled from Catering Staff → Flight Purser → Cabin Crew**

---

## 🔴 ADDITIONAL WORKFLOWS

### A) **CATERING STAFF MEAL EDIT** (Goes through approval)
- **Action:** Edit existing meal
- **Result:** Meal status = `pending`
- **Flow:** Meal → Catering Incharge approval → Status = `approved`

### B) **INVENTORY TRANSFER TO CATERING** (Mini Stock)
- **Personnel:** Inventory Personnel
- **Action:** Transfer products directly to catering
- **Flow:** Create Transfer → Supervisor Approves → Auto-transfer to `catering_stock`
- **Result:** Products visible in Catering Mini Stock

### C) **CABIN CREW ADDITIONAL REQUESTS**
- **Personnel:** Cabin Crew
- **Action:** Request additional items during flight
- **Flow:** Cabin Crew → Catering Staff approval → Mark as delivered
- **Result:** Additional items tracked separately

---

## 🧪 TESTING CHECKLIST

### ✅ Product Workflow
- [ ] Inventory Personnel creates product → Status: pending
- [ ] Inventory Supervisor approves → Status: approved
- [ ] Product appears in active inventory

### ✅ Meal Workflow
- [ ] Catering Staff creates meal → Status: pending
- [ ] Catering Incharge approves → Status: approved
- [ ] Catering Staff edits meal → Status: pending
- [ ] Catering Incharge approves edit → Status: approved

### ✅ Request Fulfillment Workflow
- [ ] Catering Staff creates request → pending_inventory
- [ ] Inventory Personnel forwards → pending_supervisor
- [ ] Inventory Supervisor approves → sent_to_security
- [ ] Security authenticates → security_approved
- [ ] Stock deducted from main inventory
- [ ] Catering Incharge approves → catering_approved
- [ ] Stock added to catering_stock
- [ ] Catering Staff sends to ramp → sent_to_ramp
- [ ] Ramp Agent dispatches → dispatched
- [ ] Cabin Crew loads → loaded

### ✅ Status Visibility
- [ ] Each role sees correct pending items on dashboard
- [ ] Status badges show correctly at each step
- [ ] Counts update in real-time

---

## 🎯 CRITICAL CHECKPOINTS

### Database Integrity
- `products.quantity_in_stock` decreases when Security issues
- `catering_stock.quantity_available` increases when Catering Incharge approves
- `requests.status` progresses in correct order
- `stock_movements` logged at each transfer

### Permission Checks
- Each role can only access their authorized routes
- Approval buttons only visible to authorized roles
- Status transitions follow strict rules

### Stock Tracking
- Main Inventory → Security Issue → Catering Stock → Flight
- No stock lost in transfers
- Audit trail via `stock_movements` table

---

## 🚨 COMMON ISSUES & FIXES

### Issue 1: Product not showing after creation
**Cause:** Status still `pending`
**Fix:** Inventory Supervisor must approve first

### Issue 2: Request stuck at Security
**Cause:** Security hasn't authenticated
**Fix:** Security must click "Authenticate" to proceed

### Issue 3: Catering Staff can't see approved stock
**Cause:** Catering Incharge hasn't approved receipt
**Fix:** Catering Incharge must approve in Receipts section

### Issue 4: Meal edit doesn't require approval
**Cause:** Controller not setting status to pending
**Fix:** ✅ FIXED - Now sets status = 'pending' on update

---

## 📊 WORKFLOW STATUS PROGRESSION

```
PRODUCT CREATION:
pending → approved

MEAL CREATION/EDIT:
pending → approved

REQUEST FULFILLMENT:
pending_inventory → 
pending_supervisor → 
sent_to_security → 
security_approved → 
catering_approved → 
sent_to_ramp → 
dispatched → 
loaded (by Flight Purser) →
delivered (served to customers by Cabin Crew)
```

---

## 🎓 TRAINING NOTES

### For Inventory Personnel:
- Create products (await Supervisor approval)
- Review Catering Staff requests
- Forward to Supervisor for approval

### For Inventory Supervisor:
- Approve products created by Personnel
- Approve/adjust request quantities
- Send approved requests to Security

### For Security Staff:
- Authenticate requests from Supervisor
- Deduct stock from main inventory
- Send to Catering Incharge

### For Catering Incharge:
- Approve meals created/edited by Catering Staff
- Approve receipts from Security
- Make stock available to Catering Staff

### For Catering Staff:
- Create meals (await Incharge approval)
- Create requests for flights
- View approved requests
- Send to Ramp Agent

### For Ramp Agent:
- Dispatch approved requests to aircraft
- Coordinate with Flight Purser

### For Flight Purser:
- View products/meals before loading
- Load items onto aircraft
- Hand over to Cabin Crew

### For Cabin Crew:
- View loaded items on aircraft
- Serve products/meals to passengers
- Mark as "Served to Customers"
- Request additional items if needed

---

**✅ ALL WORKFLOWS NOW FOLLOWING PROPER APPROVAL FLOW**
**✅ MEAL CREATE & EDIT BOTH REQUIRE CATERING INCHARGE APPROVAL**
**✅ STATUS BADGES SHOWING AT ALL LEVELS**
