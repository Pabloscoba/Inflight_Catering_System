# NOTIFICATION BUTTONS TESTING GUIDE

## Jinsi ya Kutest Notification Buttons Kwa Kila Role

### 🎯 LENGO (GOAL)
Kuhakikisha notification buttons zinafanya kazi bila kuleta error ya "user does not have a role to access this" kwa kila role.

---

## 📋 TESTING CHECKLIST

### 1️⃣ ADMIN ROLE
**Test Steps:**
1. Login kama Admin user
2. Angalia notifications panel (bell icon)
3. Bonyeza notifications za:
   - New Request Created → Should go to admin.requests.show
   - Request Needs Approval → Should go to admin.requests.pending
   - Low Stock Alert → Should go to admin.products.edit
   - New Product Created → Should go to admin.products.index
   - Request Rejected → Should go to admin.requests.show
   
**Expected Result:** ✅ All buttons work, no "Unauthorized" errors

---

### 2️⃣ INVENTORY SUPERVISOR ROLE
**Test Steps:**
1. Login kama Inventory Supervisor
2. Angalia notifications za:
   - New Product Created → Should go to inventory-supervisor.approvals.products
   - Stock Movement Pending → Should go to inventory-supervisor.approvals.movements
   - Request Needs Approval → Should go to inventory-supervisor.requests.pending
   - Low Stock Alert → Should go to inventory-supervisor.products.index
   
**Expected Result:** ✅ All buttons work, no "Unauthorized" errors

---

### 3️⃣ INVENTORY PERSONNEL ROLE
**Test Steps:**
1. Login kama Inventory Personnel
2. Angalia notifications za:
   - Low Stock Alert → Should go to inventory-personnel.products.show
   - Request Needs Review → Should go to inventory-personnel.requests.pending
   - Product Approved → Should go to inventory-personnel.products.show
   - Stock Movement Approved → Should go to inventory-personnel.stock-movements.index
   
**Expected Result:** ✅ All buttons work, no "Unauthorized" errors

---

### 4️⃣ CATERING STAFF ROLE
**Test Steps:**
1. Login kama Catering Staff
2. Angalia notifications za:
   - New Request Created → Should go to catering-staff.requests.show
   - Request Rejected → Should go to catering-staff.requests.show
   - Request Approved → Should go to catering-staff.requests.show
   
**Expected Result:** ✅ All buttons work, no "Unauthorized" errors

---

### 5️⃣ CATERING INCHARGE ROLE
**Test Steps:**
1. Login kama Catering Incharge
2. Angalia notifications za:
   - New Request Created → Should go to catering-incharge.dashboard
   - Request Approved → Should go to catering-incharge.requests.pending or dashboard
   - Request Authenticated → Should go to catering-incharge.dashboard
   
**Expected Result:** ✅ All buttons work, no "Unauthorized" errors

---

### 6️⃣ FLIGHT PURSER ROLE
**Test Steps:**
1. Login kama Flight Purser
2. Angalia notifications za:
   - Request Loaded → Should go to flight-purser.requests.show
   - Flight Cleared → Should go to flight-purser.dashboard
   - Request Approved → Should go to flight-purser.dashboard
   
**Expected Result:** ✅ All buttons work, no "Unauthorized" errors

---

### 7️⃣ CABIN CREW ROLE
**Test Steps:**
1. Login kama Cabin Crew
2. Angalia notifications za:
   - Request Loaded → Should go to cabin-crew.dashboard
   - Product Return Authenticated → Should go to cabin-crew.returns.show
   - Request Approved → Should go to cabin-crew.dashboard
   
**Expected Result:** ✅ All buttons work, no "Unauthorized" errors

---

### 8️⃣ RAMP DISPATCHER ROLE
**Test Steps:**
1. Login kama Ramp Dispatcher
2. Angalia notifications za:
   - Product Return Initiated → Should go to ramp-dispatcher.returns.index
   - Request Loaded → Should go to ramp-dispatcher.dashboard
   - Request Authenticated → Should go to ramp-dispatcher.dashboard
   
**Expected Result:** ✅ All buttons work, no "Unauthorized" errors

---

### 9️⃣ SECURITY STAFF ROLE
**Test Steps:**
1. Login kama Security Staff
2. Angalia notifications za:
   - Request Approved → Should go to security-staff.requests.awaiting-authentication
   - Product Return Initiated → Should go to security-staff.dashboard
   
**Expected Result:** ✅ All buttons work, no "Unauthorized" errors

---

### 🔟 FLIGHT DISPATCHER ROLE
**Test Steps:**
1. Login kama Flight Dispatcher
2. Angalia notifications za:
   - Request Approved → Should go to flight-dispatcher.requests.show
   - Request Authenticated → Should go to flight-dispatcher.requests.show (if role logic exists)
   
**Expected Result:** ✅ All buttons work, no "Unauthorized" errors

---

## 🧪 QUICK TEST SCRIPT

Ukitaka kutest kwa haraka, unaweza kufanya hivi:

```bash
# 1. Login kama user wa kila role
# 2. Visit notifications page
php artisan serve
# Open browser: http://localhost:8000/notifications
```

## 📊 ERROR CHECKING

### Kama unaona error hii:
```
"Unauthorized - You do not have permission to access this resource"
```

**Angalia:**
1. Je, user ana role sahihi?
2. Je, route iko defined kwenye routes/web.php?
3. Je, middleware ya `check_role_or_permission` iko set vizuri?
4. Je, notification class inaangalia role kabla ya kuweka action_url?

### Kama button haifanyi kitu:
- Check browser console kwa errors
- Angalia kama JavaScript ya notification click inafanya kazi
- Verify action_url iko defined kwenye notification data

## ✅ SUCCESS INDICATORS

Kama yote yamefanya kazi vizuri:
- ✓ No "Unauthorized" errors
- ✓ User anapelekwa kwa page sahihi
- ✓ Page inaonyesha data inayofaa kwa role yake
- ✓ Notification inabaki marked as read

## 🚨 COMMON ISSUES & SOLUTIONS

### Issue 1: "Route not found"
**Solution:** Angalia kama route iko defined kwenye routes/web.php

### Issue 2: "Unauthorized" error
**Solution:** Angalia kama user ana role au permission inayofaa

### Issue 3: Button doesn't work
**Solution:** Check JavaScript console na verify action_url iko sahihi

### Issue 4: Redirects to wrong page
**Solution:** Angalia notification class logic ya role checking

---

## 📞 SUPPORT

Kama una issues, angalia:
1. [NOTIFICATION-FIXES-COMPLETED.md](NOTIFICATION-FIXES-COMPLETED.md) - Summary ya changes
2. [NOTIFICATION-ISSUES-ANALYSIS.md](NOTIFICATION-ISSUES-ANALYSIS.md) - Original issues analysis
3. Check app/Notifications/*.php files - All notification classes

---

**Testing Priority:** ⭐⭐⭐⭐⭐ CRITICAL  
**Testing Time Estimate:** ~30 minutes for all roles  
**Recommended:** Test after deployment to ensure all roles work correctly
