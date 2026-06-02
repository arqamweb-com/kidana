**Subject:** Sandbox Issue — Test Card 5078 0967 0005 3444 Returns PAID Instead of REJECTED

---

Dear FawryPay Support Team,

I am writing to report a bug in your staging/sandbox environment that prevents us from properly testing the failed payment scenario using your documented test cards.

---

## Issue Summary

According to your official documentation at:
https://developer.fawrystaging.com/docs/express-checkout/testing/testing

The test card **5078 0967 0005 3444** (Yellow Card) is documented to produce a **"Payment rejection from service provider"** result. However, in practice, this card consistently returns `orderStatus: "PAID"` instead of `REJECTED`.

---

## Environment

- Integration: FawryPay Express Checkout (JS Plugin)
- Sandbox JS: `https://atfawry.fawrystaging.com/atfawry/plugin/assets/payments/js/fawrypay-payments.js`
- Status Endpoint: `https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/status/v2`
- Display Mode tested: `DISPLAY_MODE.POPUP` and `DISPLAY_MODE.REDIRECT`

---

## Steps to Reproduce

1. Initiate a checkout session using the FawryPay JS plugin.
2. Enter the following test card details on the payment form:
   - **Card Number:** 5078 0967 0005 3444
   - **Expiry:** 01/39
   - **CVV:** 100
   - **Cardholder Name:** Fawry Test
   - **OTP:** 123456
3. Complete the payment flow.

**Expected Result:** `orderStatus: "REJECTED"` or `"FAILED"`

**Actual Result:** `orderStatus: "PAID"` — payment is marked as successful.

---

## Additional Finding — ACS Simulation Page Not Appearing

Your documentation states:

> *"To test Failed Credit Card scenario, just select of the available option in the dropdown menu in the below image."*

This refers to an **ACS Simulation Page** with a dropdown to select the transaction outcome. However, this page does **not appear at all** during the checkout flow — neither in `POPUP` mode nor in `REDIRECT` mode. The payment completes without any 3DS/ACS simulation step being shown.

---

## Confirmed via Postman

We also tested the payment status inquiry directly via Postman using the Get Payment Status V2 endpoint:

```
GET https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/status/v2
    ?merchantCode={merchantCode}
    &merchantRefNumber={ref}
    &signature={sha256_signature}
```

The response returned `orderStatus: "PAID"` for payments made with the `3444` card, confirming the issue is on the sandbox server side — not in our frontend or signature implementation.

---

## Impact

We are unable to test our application's handling of failed/rejected payments in the sandbox environment, which blocks us from going to production with confidence.

---

## Request

Could you please investigate and fix the sandbox behavior so that:

1. The card **5078 0967 0005 3444** correctly returns `REJECTED` or `FAILED` as documented.
2. The **ACS Simulation Page** with the outcome dropdown appears as described in your documentation.

Please let us know if you need any additional information, such as merchant reference numbers or request logs.

Thank you for your support.

Best regards,
