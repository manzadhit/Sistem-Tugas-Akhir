.

List controller (disarankan)

Mahasiswa:
Mahasiswa/DashboardController
Mahasiswa/PembimbingRequestController
Mahasiswa/BimbinganController
Mahasiswa/PengujiRequestController
Mahasiswa/UjianApplicationController
Kajur:
Kajur/DashboardController
Kajur/PembimbingApprovalController
Kajur/PengujiApprovalController
Kajur/RecommendationController (opsional, jika rekomendasi dipisah)
Dosen:
Dosen/DashboardController
Dosen/BimbinganReviewController
Dosen/UjianInvitationController
Dosen/UjianScheduleController
Dosen/UjianScoreController
Dosen/PublikasiController
Admin:
Admin/DashboardController
Admin/MahasiswaController
Admin/DosenController
Admin/PublikasiController
Admin/UjianRequirementVerificationController
Admin/UjianInvitationController
Admin/UjianResultVerificationController
List route (disarankan, ringkas per aktor)
Gunakan group per file: mahasiswa.php, kajur.php, dosen.php, admin.php.

Mahasiswa (prefix /mahasiswa, middleware auth, role:mahasiswa)

GET /mahasiswa/dashboard → Mahasiswa/DashboardController@index (status progres)
GET /mahasiswa/pembimbing/request → PembimbingRequestController@create
POST /mahasiswa/pembimbing/request → PembimbingRequestController@store
GET /mahasiswa/bimbingan → BimbinganController@index
POST /mahasiswa/bimbingan/laporan → BimbinganController@store
PUT /mahasiswa/bimbingan/laporan/{laporan} → BimbinganController@update
GET /mahasiswa/penguji/request → PengujiRequestController@create
POST /mahasiswa/penguji/request → PengujiRequestController@store
GET /mahasiswa/ujian/apply → UjianApplicationController@create
POST /mahasiswa/ujian/apply → UjianApplicationController@store
Kajur (prefix /kajur, middleware auth, role:kajur)

GET /kajur/dashboard → Kajur/DashboardController@index
GET /kajur/pembimbing/requests → PembimbingApprovalController@index
GET /kajur/pembimbing/requests/{id} → PembimbingApprovalController@show
POST /kajur/pembimbing/requests/{id}/assign → PembimbingApprovalController@assign
GET /kajur/pembimbing/rekomendasi → RecommendationController@pembimbing (opsional)
GET /kajur/penguji/requests → PengujiApprovalController@index
POST /kajur/penguji/requests/{id}/assign → PengujiApprovalController@assign
GET /kajur/penguji/rekomendasi → RecommendationController@penguji (opsional)
Dosen (prefix /dosen, middleware auth, role:dosen)

GET /dosen/dashboard → Dosen/DashboardController@index
GET /dosen/bimbingan → BimbinganReviewController@index
GET /dosen/bimbingan/{id} → BimbinganReviewController@show
POST /dosen/bimbingan/{id}/review → BimbinganReviewController@review (ACC/Revisi)
GET /dosen/ujian/undangan → UjianInvitationController@index
GET /dosen/ujian/jadwal → UjianScheduleController@index
POST /dosen/ujian/{id}/nilai → UjianScoreController@store
Resource /dosen/publikasi → Dosen/PublikasiController (index/create/store/edit/update/destroy)
Admin (prefix /admin, middleware auth, role:admin)

GET /admin/dashboard → Admin/DashboardController@index
Resource /admin/mahasiswa → Admin/MahasiswaController
Resource /admin/dosen → Admin/DosenController
GET /admin/publikasi → Admin/PublikasiController@index
PATCH /admin/publikasi/{id}/verify → Admin/PublikasiController@verify
GET /admin/ujian/requirements → UjianRequirementVerificationController@index
PATCH /admin/ujian/requirements/{id} → UjianRequirementVerificationController@update
GET /admin/ujian/invitations/create → UjianInvitationController@create
POST /admin/ujian/invitations → UjianInvitationController@store
GET /admin/ujian/results → UjianResultVerificationController@index
PATCH /admin/ujian/results/{id} → UjianResultVerificationController@update
