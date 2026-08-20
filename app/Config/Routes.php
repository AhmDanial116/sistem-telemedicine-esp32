<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get(
    '/',
    'IdentificationController::index'
);

$routes->post(
    'identify/patient',
    'IdentificationController::patient'
);

$routes->post(
    'identify/doctor',
    'IdentificationController::doctor'
);

$routes->post(
    'identify/admin',
    'IdentificationController::admin'
);

$routes->get(
    'logout',
    'IdentificationController::logout'
);

//----------------------------------------------------
// PASIEN
//----------------------------------------------------

$routes->group(
    'patient',
    [
        'filter' => 'role:patient',
    ],
    static function ($routes) {
        $routes->get(
            'dashboard',
            'PatientController::dashboard'
        );

        $routes->post(
            'measurement/start',
            'PatientController::startMeasurement'
        );

        $routes->get(
            'measurement/status/(:num)',
            'PatientController::measurementStatus/$1'
        );

        $routes->get(
            'measurement/latest',
            'PatientController::latestMeasurement'
        );

        $routes->get(
            'notes',
            'PatientController::medicalNotes'
        );

        $routes->post(
            'consultation/request',
            'PatientController::storeConsultationRequest'
        );
    }
);

//----------------------------------------------------
// DOKTER
//----------------------------------------------------

$routes->group(
    'doctor',
    [
        'filter' => 'role:doctor',
    ],
    static function ($routes) {
        $routes->get(
            'dashboard',
            'DoctorController::dashboard'
        );

        $routes->get(
            'patient/(:num)',
            'DoctorController::patientDetail/$1'
        );

        $routes->post(
            'patient/(:num)/note',
            'DoctorController::storeMedicalNote/$1'
        );
        $routes->get(
            'consultations',
            'DoctorController::consultations'
        );

        $routes->post(
            'consultation/(:num)/update',
            'DoctorController::updateConsultation/$1'
        );

        $routes->get(
            'notification/(:num)/open',
            'DoctorController::openNotification/$1'
        );
        $routes->get(
            'patient/(:num)/report/pdf',
            'DoctorController::exportPatientPdf/$1'
        );
    }
);

//----------------------------------------------------
// ADMIN
//----------------------------------------------------

$routes->group(
    'admin',
    [
        'filter' => 'role:admin',
    ],
    static function ($routes) {
        $routes->get(
            'dashboard',
            'AdminController::dashboard'
        );
        $routes->post(
            'assignment/save',
            'AdminController::assignDoctor'
        );
        $routes->post(
            'doctor/store',
            'AdminController::storeDoctor'
        );

        $routes->post(
            'doctor/(:num)/update',
            'AdminController::updateDoctor/$1'
        );

        $routes->post(
            'doctor/(:num)/toggle-status',
            'AdminController::toggleDoctorStatus/$1'
        );

        $routes->get(
            'device/(:num)/assign',
            'AdminController::assignDeviceForm/$1'
        );

        $routes->post(
            'device/(:num)/assign',
            'AdminController::assignDevice/$1'
        );
        $routes->post(
            'patient/store',
            'AdminController::storePatient'
        );
    }
);

//----------------------------------------------------
// API
//----------------------------------------------------

$routes->group(
    'api/v1',
    [
        'namespace' => 'App\Controllers\API',
    ],
    static function ($routes) {
        $routes->get(
            'device/(:segment)/request',
            'DeviceController::request/$1'
        );

        $routes->get(
            'device/(:segment)',
            'DeviceController::show/$1'
        );
        $routes->post(
            'request/status',
            'RequestController::updateStatus'
        );

        $routes->get(
            'request/(:num)',
            'RequestController::show/$1'
        );
        $routes->post(
            'measurement/upload',
            'MeasurementController::upload'
        );

        $routes->get(
            'measurement/latest/(:segment)',
            'MeasurementController::latest/$1'
        );
    }
);

$routes->get(
    'telegram-test',
    'TelegramTestController::index'
);
