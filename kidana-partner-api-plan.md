# خطة Partner API لكدانة

المشروع: `/Applications/MAMP/htdocs/dashboard` (Laravel 13 + Filament 5 + Sanctum 4، ريبو arqamweb-com/kidana)

## اللي موجود في الكود
- الموديلز: Service، Package (ليه service_id و destination_id)، Hotel ← HotelRoom (فيها `availableRoomsBetween` وقفل الغرف أثناء الدفع)، Booking، Payment (فوري).
- كل الحجوزات بتمر على `App\Services\Bookings\BookingService`: الدوال `createCustomBooking` و`createFawryBooking` و`createHotelBooking`.
- `routes/api.php` فاضي، وجدول Sanctum `personal_access_tokens` موجود.
- موديل `Partner` الموجود خاص بلوجوهات الشركاء على الموقع (name/image) ومالوش علاقة بالـ API. هنعمل موديل منفصل اسمه `ApiClient` ومش هنلمس الموجود.

## البنية
- الـ API جوه نفس التطبيق تحت `/api/v1` (Eloquent API Resources + versioning).
- `ApiClient`: name، contact_email، is_active، markup_percent، allowed_abilities، webhook_url، webhook_secret، ip_allowlist (اختياري). بيستخدم `HasApiTokens`.
- الـ Abilities: `catalog:read` و`bookings:create` و`bookings:read`.
- مصدر الطلب بيتعرف من التوكن نفسه. كل request بيتسجل في جدول `api_request_logs`.

## الـ Endpoints
- GET `/services` و`/services/{slug}`
- GET `/packages` (فلتر: service، destination، date، price) و`/packages/{slug}`
- GET `/hotels` و`/hotels/{slug}` (معاها الغرف)
- GET `/hotels/{slug}/availability?check_in&check_out&adults&children`: بتستخدم `availableRoomsBetween`
- POST `/bookings` (package أو hotel_room) وبتتطلب الهيدر `Idempotency-Key`
- GET `/bookings/{reference}` و POST `/bookings/{reference}/cancel`
- اللغة بتتحدد من `Accept-Language` (ar/en)، والأسعار بتطلع بعد إضافة الماركب بتاع الشريك.

## الحجز
- عمودين جداد في bookings: `source` (web/api) و`api_client_id` و`external_reference`.
- الـ Controller بتاع الـ API بينادي نفس `BookingService`، فمفيش لوجيك حجز متكرر، وقفل الغرف هو هو.
- الحجز بيظهر في BookingResource في Filament، وبيتفلتر حسب المصدر والشريك.
- إشعار للأدمن مع كل حجز جاي من الـ API (إيميل، وبعدين واتساب عن طريق n8n).
- Webhook للشريك لما الحالة تتغير (paid/cancelled/expired)، موقّع HMAC-SHA256 وبيتبعت من Job فيه retries.

## الدفع (قرار مفتوح)
- أ) الشريك بيحصّل من عميله وبيتحاسب مع كدانة (الحالة Pending/Confirmed، وكشف حساب شهري).
- ب) الـ API بيرجّع لينك دفع فوري والعميل بيدفع بنفسه (نفس `createFawryBooking`).
- ج) الاتنين، والاختيار حسب إعداد كل شريك. ده المقترح.

## الداشبورد
- المرحلة 1 في Filament: `ApiClientResource` (إنشاء التوكن وإلغاؤه، يظهر مرة واحدة بس، والماركب، والصلاحيات)، وصفحة لوج الطلبات، وفلتر الحجوزات حسب الشريك، وويدجت أرقام لكل شريك.
- المرحلة 2: بورتال للشريك نفسه (Filament panel تاني اسمه `partner`). بيتأجل لحد ما يبقى فيه 3 أو 4 شركاء فعليين.
- التوثيق: OpenAPI أو Scribe على `/docs/api`.

## مراحل التنفيذ
1. ApiClient + التوكنز + middleware (ability، throttle، logging) + endpoints الكتالوج + Resources + تستات Pest.
2. POST bookings + idempotency + أعمدة source + فلاتر Filament + إشعار الأدمن.
3. Webhooks للشركاء + صفحة التوثيق.
4. Widget (JS/iframe) للشركاء غير التقنيين.
5. بورتال الشريك.
