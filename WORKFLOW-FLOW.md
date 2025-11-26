# Mtiririko wa Approval Workflow (Request Flow)

## Ufafanuzi wa Flow Mpya (New Workflow)

Mfumo umeundwa kuwa na hatua za approval zilizo wazi na **Security authentication** kabla bidhaa hazijafika kwa Catering Staff.

### Hatua za Mtiririko (Flow Steps):

```
1. CATERING STAFF
   ↓ (Anaomba bidhaa - Create Request)
   Status: pending_inventory

2. INVENTORY PERSONNEL
   ↓ (Ana-review na ku-forward kwa Supervisor)
   Status: pending_supervisor

3. INVENTORY SUPERVISOR  
   ↓ (Ana-approve request - Approve quantities)
   Status: supervisor_approved

4. INVENTORY PERSONNEL
   ↓ (Ana-forward kwa Security - Forward to Security)
   Status: sent_to_security

5. SECURITY STAFF
   ↓ (Ana-authenticate na ku-issue stock kutoka main inventory)
   Status: security_approved
   [Stock inatolewa hapa kutoka main inventory]

6. CATERING INCHARGE
   ↓ (Ana-approve na ku-create catering stock records)
   Status: catering_approved
   [Bidhaa ziko tayari kwa Catering Staff]

7. CATERING STAFF
   (Anaweza ku-mark as received na ku-record usage/returns)
```

---

## Maelezo ya Kila Hatua

### 1️⃣ **Catering Staff - Create Request**
- **Nini inafanyika:** Catering Staff anaomba bidhaa kwa ajili ya flight fulani.
- **Status:** `pending_inventory`
- **View:** `catering-staff/requests/create.blade.php`
- **Controller:** `CateringStaff\RequestController@store`

### 2️⃣ **Inventory Personnel - Review & Forward to Supervisor**
- **Nini inafanyika:** Inventory Personnel ana-review request kutoka Catering Staff na ku-forward kwa Supervisor.
- **Status baada ya forward:** `pending_supervisor`
- **View:** `inventory-personnel/requests/pending.blade.php`
- **Controller:** `InventoryPersonnel\RequestController@forwardToSupervisor`

### 3️⃣ **Inventory Supervisor - Approve Request**
- **Nini inafanyika:** Supervisor ana-review request na ku-approve quantities (anaweza kubadilisha kiasi).
- **Status baada ya approval:** `supervisor_approved`
- **View:** `admin/requests/approve.blade.php` (au pending list)
- **Controller:** `Admin\RequestController@approve`

### 4️⃣ **Inventory Personnel - Forward to Security**
- **Nini inafanyika:** Baada ya supervisor ku-approve, Inventory Personnel ana-forward request kwa Security. **Hakuna stock inatolewa hapa.**
- **Status baada ya forward:** `sent_to_security`
- **View:** `inventory-personnel/requests/supervisor-approved.blade.php`
- **Controller:** `Admin\RequestController@forwardToSecurity`

### 5️⃣ **Security Staff - Authenticate & Issue Stock**
- **Nini inafanyika:** Security Staff ana-verify request, authenticate, na **ku-issue stock kutoka main inventory**. Stock inatolewa kutoka inventory kuu hapa na ina-decrement.
- **Status baada ya authentication:** `security_approved`
- **View:** `security-staff/requests/awaiting-authentication.blade.php`
- **Controller:** `SecurityStaff\RequestController@authenticateRequest`
- **Action:** 
  - Ku-create `StockMovement` (type: 'issued')
  - Ku-decrement `quantity_in_stock` kwenye `products` table

### 6️⃣ **Catering Incharge - Approve Receipt**
- **Nini inafanyika:** Catering Incharge ana-receive stock kutoka Security na ku-approve. Hapa ina-create records za `catering_stock` ambazo zinaonyesha bidhaa ziko tayari kwa Catering Staff.
- **Status baada ya approval:** `catering_approved`
- **View:** `catering-incharge/requests/pending.blade.php`
- **Controller:** `CateringIncharge\RequestApprovalController@approveRequest`
- **Action:**
  - Ku-create `CateringStock` entries (quantity_received, quantity_available)
  - Request inakuwa final approved (`catering_approved`)

### 7️⃣ **Catering Staff - Mark Received & Use**
- **Nini inafanyika:** Catering Staff wana-view approved requests, wana-mark kama received, na wana-record usage/returns.
- **Status:** `catering_approved` → (Catering Staff wanaweza ku-mark `received`)
- **View:** `catering-staff/requests/show.blade.php`
- **Controller:** `CateringStaff\RequestController@markReceived`, `recordUsage`, `returnItems`

---

## Sample Flights (Zinapatikana kwenye Test Data)

Zimeundwa flights tatu za demo:

| Flight Number | Route         | Departure             | Status    |
|---------------|---------------|-----------------------|-----------|
| DF100         | JRO → DAR     | Kesho 8:00 AM         | Scheduled |
| DF201         | DAR → ZNZ     | Kesho kutwa 2:00 PM   | Scheduled |
| DF302         | ZNZ → JRO     | Kesho 3 days 10:30 AM | Scheduled |

Catering Staff anaweza ku-select mojawapo ya hizo wakati wa ku-create request.

---

## Jinsi ya Ku-test Flow Mzima

### Hatua 1: Endesha Test Script
```powershell
php create-single-flow-test.php
```

Script hii ita:
- Unda watumiaji (staff, supervisor, inventory, security, incharge) kama hawajawahi kuundwa
- Unda product moja (Test Chicken Meal - 500 units)
- Unda flights tatu (DF100, DF201, DF302)
- Tengeneza request moja na ku-simulate flow mzima kutoka Catering Staff hadi Catering Incharge approval

### Hatua 2: Login kwa Roles Tofauti

#### Login Credentials (Password: `password`)

| Role                  | Email                    |
|-----------------------|--------------------------|
| Catering Staff        | staff@example.test       |
| Inventory Supervisor  | supervisor@example.test  |
| Inventory Personnel   | inventory@example.test   |
| Security Staff        | security@example.test    |
| Catering Incharge     | incharge@example.test    |

**Au tumia credentials za default (seeded):**

| Role                  | Email                               | Password        |
|-----------------------|-------------------------------------|-----------------|
| Admin                 | admin@inflightcatering.com          | Admin@123       |
| Inventory Personnel   | inventory@inflightcatering.com      | Inventory@123   |
| Inventory Supervisor  | supervisor@inflightcatering.com     | Supervisor@123  |
| Catering Incharge     | catering@inflightcatering.com       | Catering@123    |
| Catering Staff        | staff@inflightcatering.com          | Staff@123       |
| Security Staff        | security@inflightcatering.com       | Security@123    |

### Hatua 3: Angalia Flow kwenye UI

1. **Login kama Catering Staff** → Dashboard → Create Request → Select flight (DF100) na product → Submit
2. **Login kama Inventory Supervisor** → Pending Requests → Approve quantities
3. **Login kama Inventory Personnel** → Supervisor Approved → Forward to Security
4. **Login kama Security Staff** → Awaiting Authentication → Authenticate (stock issued here)
5. **Login kama Catering Incharge** → Pending Requests → Approve (catering stock created)
6. **Login kama Catering Staff** → View Approved Requests → Mark Received / Record Usage

---

## Database Tables Zinazohusika

### `requests`
- `status`: pending_supervisor → supervisor_approved → sent_to_security → security_approved → catering_approved
- `approved_by`, `approved_date`: Inawekwa na Security/Catering Incharge

### `request_items`
- `quantity_requested`: Kiasi alichoomba Catering Staff
- `quantity_approved`: Kiasi aliye-approve Supervisor

### `stock_movements`
- `type`: 'issued' (Security ana-create wakati wa authentication)
- `user_id`: Security Staff ID

### `catering_stock`
- Ina-create na Catering Incharge baada ya Security approval
- `quantity_received`, `quantity_available`: Track available stock kwa Catering Staff
- `received_by`: NULL mpaka Catering Staff a-mark kama received

### `products`
- `quantity_in_stock`: Ina-decrement wakati Security ana-issue stock

---

## Summary

Mfumo huu una **Inventory Personnel** kama gatekeeper wa kwanza na **Security authentication** kama checkpoint kabla bidhaa hazijafika kwa Catering Staff. Flow ni:

**Catering Staff → Inventory Personnel → Supervisor → Inventory Personnel → Security → Catering Incharge → Catering Staff**

Kila hatua ina role yake maalum na stock inatolewa **baada ya Security authentication** tu, sio mapema.
