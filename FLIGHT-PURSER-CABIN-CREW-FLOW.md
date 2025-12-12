# ✈️ FLIGHT PURSER & CABIN CREW WORKFLOW
## Updated Roles & Responsibilities

---

## 🎯 ROLE CLARIFICATION

### **FLIGHT PURSER** (purser@inflightcatering.com)
**Primary Responsibility:** Load catering supplies onto aircraft

**Actions:**
1. **View Products/Meals** - Review all items dispatched from Ramp Agent
2. **Load onto Aircraft** - Physically load items onto the plane
3. **Confirm Loading** - Mark request as `loaded` in system
4. **Coordinate with Cabin Crew** - Hand over loaded items

**Dashboard Features:**
- ✈️ Requests to Load onto Aircraft (status: `dispatched`)
- 👁️ View Products button - Review items before loading
- 📦 Load onto Aircraft button - Confirm loading
- ✅ Recently Loaded Requests - Track loading history

---

### **CABIN CREW** (cabin@inflightcatering.com)
**Primary Responsibility:** Serve products/meals to customers during flight

**Actions:**
1. **Receive Loaded Items** - Accept items loaded by Flight Purser
2. **View Items** - Review all products/meals available on aircraft
3. **Serve Passengers** - Provide service to customers during flight
4. **Mark as Served** - Confirm all items served to customers (status: `delivered`)

**Dashboard Features:**
- ✈️ Supplies Loaded onto Aircraft (status: `loaded`)
- 👁️ View Items button - See all available products/meals
- 🍽️ Served to Customers button - Mark service complete
- 📊 Statistics: To Receive, Served to Passengers, Flights Handled

---

## 🔄 COMPLETE WORKFLOW (Final Steps)

### Step 7️⃣: **RAMP AGENT** → Dispatch
```
Status: ready_for_dispatch → dispatched
Action: Mark as Dispatched
Next: Send to Flight Purser
```

### Step 8️⃣: **FLIGHT PURSER** → Load onto Aircraft
```
Status: dispatched → loaded
Actions:
  1. View Products - Review items
  2. Load onto Aircraft - Physical loading
  3. Confirm in system
Database Updates:
  - requests.status = 'loaded'
  - loaded_by = Flight Purser ID
  - loaded_at = timestamp
Next: Hand over to Cabin Crew
```

### Step 9️⃣: **CABIN CREW** → Serve to Customers
```
Status: loaded → delivered
Actions:
  1. View Items - See all products/meals
  2. Serve to passengers during flight
  3. Mark as "Served to Customers"
Database Updates:
  - requests.status = 'delivered'
  - delivered_by = Cabin Crew ID
  - delivered_at = timestamp
Result: Service cycle complete ✅
```

---

## 📊 STATUS FLOW

```
Catering Staff Request
    ↓
pending_inventory
    ↓
pending_supervisor
    ↓
sent_to_security
    ↓
security_approved
    ↓
catering_approved
    ↓
sent_to_ramp
    ↓
dispatched (Ramp Agent)
    ↓
loaded (FLIGHT PURSER) ⭐
    ↓
delivered (CABIN CREW) ⭐
```

---

## 🎬 USER ACTIONS

### **FLIGHT PURSER Dashboard**

**Pending Actions Table:**
| Request ID | Flight | Route | Departure | Items | Dispatched | Action |
|------------|--------|-------|-----------|-------|------------|--------|
| #123 | AA301 | DAR→JRO | Dec 1, 14:00 | 15 items | Dec 1, 10:30 | 👁️ View Products <br> 📦 Load onto Aircraft |

**Buttons:**
- **👁️ View Products** → Opens request details showing all items
- **📦 Load onto Aircraft** → Confirms loading (requires confirmation dialog)

---

### **CABIN CREW Dashboard**

**Loaded Supplies Table:**
| Request ID | Flight | Route | Departure | Items | Loaded At | Action |
|------------|--------|-------|-----------|-------|-----------|--------|
| #123 | AA301 | DAR→JRO | Dec 1, 14:00 | 15 items | Dec 1, 12:00 | 👁️ View Items <br> 🍽️ Served to Customers |

**Buttons:**
- **👁️ View Items** → Opens request details showing all products/meals
- **🍽️ Served to Customers** → Marks as delivered (requires confirmation dialog)

---

## 🔐 PERMISSIONS

### Flight Purser Can:
- ✅ View dispatched requests
- ✅ View product/meal details
- ✅ Mark requests as loaded
- ✅ View loading history
- ❌ Cannot modify products
- ❌ Cannot create new requests

### Cabin Crew Can:
- ✅ View loaded requests
- ✅ View item details
- ✅ Mark items as served/delivered
- ✅ Request additional items (separate workflow)
- ✅ Generate service reports
- ❌ Cannot modify products
- ❌ Cannot load items

---

## ⚡ KEY DIFFERENCES

### Before:
- **Cabin Crew** loaded items onto aircraft
- No distinction between loading and service

### After:
- **Flight Purser** loads items onto aircraft (operational role)
- **Cabin Crew** serves items to customers (service role)
- Clear separation of responsibilities
- Better tracking of loading vs. service completion

---

## 🧪 TESTING STEPS

### Test Flight Purser Actions:
1. Login as `purser@inflightcatering.com` (Password: `Purser@123`)
2. Navigate to Dashboard
3. Find request with status `dispatched`
4. Click "👁️ View Products" - verify all items visible
5. Click "📦 Load onto Aircraft"
6. Confirm loading dialog
7. Verify status changes to `loaded`
8. Check request appears in "Recently Loaded" section

### Test Cabin Crew Actions:
1. Login as `cabin@inflightcatering.com` (Password: `Cabin@123`)
2. Navigate to Dashboard
3. Find request with status `loaded`
4. Click "👁️ View Items" - verify all products/meals visible
5. Click "🍽️ Served to Customers"
6. Confirm service dialog: "Confirm all items have been served to passengers?"
7. Verify status changes to `delivered`
8. Check statistics update: "Served to Passengers" count increases

---

## 📝 NOTES

### Flight Purser Role:
- Responsible for **physical loading** of items
- Verifies items match dispatch documentation
- Ensures proper storage on aircraft
- Reports any discrepancies
- Coordinates timing with Cabin Crew

### Cabin Crew Role:
- Responsible for **customer service**
- Uses loaded items during flight
- Serves meals/products to passengers
- Tracks consumption and waste
- Can request additional items if needed
- Reports service completion

### System Benefits:
✅ Clear role separation
✅ Better accountability
✅ Accurate tracking of loading time vs. service time
✅ Improved audit trail
✅ Realistic operational workflow

---

**✅ WORKFLOW NOW REFLECTS REAL AIRLINE OPERATIONS**
**✅ FLIGHT PURSER HANDLES LOADING**
**✅ CABIN CREW HANDLES PASSENGER SERVICE**
