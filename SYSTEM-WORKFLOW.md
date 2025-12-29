# INFLIGHT CATERING SYSTEM - COMPLETE WORKFLOW

## 🛫 **REAL SYSTEM FLOW** (11 Steps)

### **STEP 1: REQUEST CREATION** 
👤 **Catering Staff**
- Creates request for flight catering products
- Selects products from main inventory (quantity_in_stock)
- Status: `pending_catering_incharge`

### **STEP 2: INITIAL APPROVAL**
👤 **Catering Incharge** 
- Reviews request from Catering Staff
- Approves or rejects with reason
- Status: `pending_catering_incharge` → `catering_approved`

### **STEP 3: SUPERVISOR APPROVAL**
👤 **Supervisor**
- Reviews approved requests from Catering Incharge
- Second-level approval for compliance
- Status: `catering_approved` → `supervisor_approved`

### **STEP 4: ITEMS ISSUED**
👤 **Inventory Personnel**
- Issues products from main warehouse
- Updates stock (quantity_in_stock decreases)
- Creates stock movement records
- Status: `supervisor_approved` → `items_issued`

### **STEP 5: CATERING STAFF RECEIVES ITEMS**
👤 **Catering Staff**
- Confirms receipt of issued items
- Checks quality and quantity
- Status: `items_issued` → `pending_final_approval`

### **STEP 6: FINAL APPROVAL**
👤 **Catering Incharge**
- Final verification after items received
- Confirms everything ready for security
- Status: `pending_final_approval` → `catering_final_approved`

### **STEP 7: SECURITY AUTHENTICATION**
👤 **Security Staff**
- **Document Assessment**: Flight number, aircraft type, requester details, cutoff time
- **Risk Assessment**: HIGH/MEDIUM/LOW based on:
  - Departure urgency (< 6 hours = +2 points)
  - Item count (> 20 items = +1 point)
  - Total quantity (> 100 units = +1 point)
- **Compliance Assessment**: Category validation, high-quantity items check
- **Integrity Assessment**: Status validation, documentation completeness
- Authenticates or rejects request
- Status: `catering_final_approved` → `security_authenticated`

### **STEP 8: RAMP DISPATCH**
👤 **Ramp Agent (Dispatcher)**
- Receives authenticated request
- Dispatches items to aircraft ramp
- Coordinates with ground crew
- Status: `security_authenticated` → `ramp_dispatched`

### **STEP 9: LOADING TO AIRCRAFT**
👤 **Flight Purser/Ramp Agent**
- Loads items onto aircraft
- Confirms successful loading
- Status: `ramp_dispatched` → `loaded`

### **STEP 10: DELIVERY CONFIRMATION**
👤 **Flight Purser**
- Confirms items delivered to cabin
- Verifies all items accounted for
- Status: `loaded` → `delivered`

### **STEP 11: SERVICE COMPLETION**
👤 **Cabin Crew**
- Records actual usage during flight
- Fills consumption form:
  - Meals served
  - Beverages served
  - Snacks served
  - Items not used/returned
- Status: `delivered` → `served`

---

## 📊 **STATUS PROGRESSION**

```
pending_catering_incharge
         ↓
catering_approved
         ↓
supervisor_approved
         ↓
items_issued
         ↓
pending_final_approval
         ↓
catering_final_approved
         ↓
security_authenticated
         ↓
ramp_dispatched
         ↓
loaded
         ↓
delivered
         ↓
served
```

---

## 👥 **ROLES & DASHBOARDS**

### **1. Catering Staff**
- Create new requests
- View request status
- Receive items from inventory
- Dashboard shows pending/approved/received requests

### **2. Catering Incharge**
- Approve/reject initial requests (Step 2)
- Give final approval after items received (Step 6)
- Monitor Low Stock Alert (main inventory)
- Dashboard shows pending requests + stock levels

### **3. Supervisor**
- Approve/reject requests from Catering Incharge
- Oversee compliance and budgets
- Dashboard shows requests awaiting approval

### **4. Inventory Personnel**
- Issue products from main warehouse
- Update stock levels (quantity_in_stock)
- Create stock movements
- Dashboard shows pending issues

### **5. Security Staff**
- **Verification Summary with Risk Breakdown**:
  - 🔴 HIGH RISK orders (score ≥ 3)
  - 🟠 MEDIUM RISK orders (score ≥ 1)
  - 🟢 LOW RISK orders (score < 1)
  - ⏰ URGENT orders (< 6 hours to departure)
- **Assessment Checklist**:
  - ✓ Document Checks (5 validations)
  - ✓ Items Verification (3 metrics)
  - ✓ Security Specific (compliance)
- Authenticate/reject requests
- Dashboard shows risk levels, urgency indicators, recent movements

### **6. Ramp Agent**
- Dispatch items to aircraft
- Confirm successful dispatch
- Dashboard shows authenticated requests ready for ramp

### **7. Flight Purser**
- Load items onto aircraft
- Confirm delivery to cabin
- Dashboard shows dispatched items

### **8. Cabin Crew**
- Record actual consumption
- Submit usage report with:
  - Meals served count
  - Beverages served count
  - Snacks served count
  - Unused/returned items
- Dashboard shows delivered items awaiting service

---

## 🔐 **SECURITY VERIFICATION FEATURES**

### **Automated Risk Scoring**
- **Departure Urgency**: +2 points if < 6 hours
- **Item Complexity**: +1 point if > 20 items
- **Quantity Volume**: +1 point if total > 100 units

### **Risk Levels**
- 🔴 **HIGH** (Score ≥ 3): Red badge, priority review
- 🟠 **MEDIUM** (Score ≥ 1): Orange badge, standard review
- 🟢 **LOW** (Score < 1): Green badge, quick review

### **Document Checks (5 validations)**
1. Has Flight Number ✓
2. Has Aircraft Type ✓
3. Has Requester ✓
4. Within Cutoff Time ✓
5. Status Valid ✓

### **Items Metrics**
- Total items count
- Items with category
- High quantity items (> 50 units)

---

## 📦 **STOCK MANAGEMENT**

### **Main Inventory (Warehouse)**
- Column: `quantity_in_stock`
- Reorder threshold: `reorder_level`
- Used for: Request validation, catering staff selection

### **Catering Stock (Catering Area)**
- Column: `catering_stock`
- Reorder threshold: `catering_reorder_level`
- Used for: Internal catering operations

### **Stock Flow**
1. Products arrive at warehouse → `quantity_in_stock` increases
2. Catering Staff requests products → validates against `quantity_in_stock`
3. Inventory issues items → `quantity_in_stock` decreases
4. Low Stock Alert triggers when `quantity_in_stock` ≤ `reorder_level`

---

## ✅ **REJECTION HANDLING**

At any approval step, requests can be REJECTED:
- Catering Incharge rejection → Status: `rejected`
- Supervisor rejection → Status: `rejected`
- Security rejection → Status: `rejected`
- Rejection reasons recorded in database
- Notifications sent to requester

---

## 📱 **NOTIFICATIONS**

System sends real-time notifications at each step:
- ✓ Request created → Notify Catering Incharge
- ✓ Request approved → Notify Supervisor
- ✓ Supervisor approved → Notify Inventory
- ✓ Items issued → Notify Catering Staff
- ✓ Final approval → Notify Security
- ✓ Security authenticated → Notify Ramp Agent
- ✓ Ramp dispatched → Notify Flight Purser
- ✓ Items loaded → Notify Cabin Crew
- ✗ Request rejected → Notify requester

---

## 🎯 **KEY FEATURES**

1. **Multi-Level Approval Chain**: 3 approvals (Incharge → Supervisor → Final)
2. **Aviation Security Standards**: Document checks, risk assessment, compliance
3. **Real-Time Stock Tracking**: Main inventory + catering stock
4. **Automated Risk Scoring**: Urgency + complexity + volume
5. **Complete Audit Trail**: Every action logged with timestamps
6. **Role-Based Access Control**: Spatie permissions for each role
7. **Visual Risk Indicators**: Color-coded badges (red/orange/green)
8. **Usage Analytics**: Post-flight consumption reporting

---

## 📊 **CURRENT TEST DATA**

- **Request #3**: Flight rt-987 (Status: loaded)
- **Request #8**: Flight boeing 2123 (Status: served - COMPLETE WORKFLOW TESTED ✓)

---

**System Version**: Laravel 12.38.1 | PHP 8.4.13
**Last Updated**: December 15, 2025
**Workflow Steps**: 11 (from creation to served)
**Roles Involved**: 8 (Catering Staff, Incharge, Supervisor, Inventory, Security, Ramp, Purser, Cabin Crew)
