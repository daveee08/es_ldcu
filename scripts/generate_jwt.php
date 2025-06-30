<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


$privateKey = "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC3hwGMQ4g5j2bs\nBeRpwM2U39fTF4lZuCZnpyEQutB5sg1x8RChkA5u8ML0MJO/9AO6Kqm8Bvq0uiTU\nLBAG1eU1nrFxNQJC0jQEEHjYsRPP6A2nKmAZWcdN39vRCX7vNRayt4Th2YSutG2k\nMkvndl02Pej/ubemfaw1QwOLVe9zHl8gOdVWXTyvDSqp8BZ3pkBTHVCZBN+iajXf\nHbcLAr7ueE5iLVkZZvfDxwVDn34COaQKaMN/VWvp2jt8onFjcUmt6T7v3rfPKdqt\n8b5rFxnaVgQB1amn9k5AYDJCAKMYADXD8oxdt0zaU1HSMbMCgj0RrY1LDE9paTai\nEURGXAjFAgMBAAECggEAAIZ+hQhzK+q5o2D4y+H9Tc/VOrmTDzBMOmsXBmRFXt2G\n8K7h3zLQQ8OJdjC0aLcVW8p2+WxtQzqueGEpMnD0hjKFRyi4cB11t01nxN6+5jzb\nEgHBueH+qRlfj4nhUC2hZ+V+YSNGcmyVcZ9+PH1aXAckIMnZa3yy00CiwDYucZ5O\nyYUsNWNJGgkXPteI2nfmuGayl3OGP1xy+ZDsBgQK+9wj9g4+Pfaqlwwe79Q01+eq\nNraI6A8J6A+sNIx5HctrflRPc/yOhi1fsAsyPKtgOsA6RaoeZYswiGk8marFEn3o\n32GmlSpmw8fRxvmOJ/W3B5/Tr6/G04NZ6OwBcMTKAQKBgQDqawgk7LTsac7vTggZ\nFyZMurcJFFyXRbBQ6L1VC7evPj3Zm8ZW9kablRx1/fSozdR/x2rHCSVmOa/ie1Qi\nScmhuQyWt4+su+gU3rAQCJkjamVYygfCg2xXB3JADXWhDroEB5qtqXPmM3481O0H\n+ybIjef+kgmCtTYdyilz1iCuAQKBgQDIbImiZGb5uCOUrs+AnR5fRliL1cmW1YNj\ny9/+FHNdZ9W5LHcTAnQPkrjPxGAVVTsFCyUaTjP36AEMJEwV6TZlGCtd+5KfcYzo\n9kGKsyOZ9kLNk5LA9Q0/jXTTldjmSmhY0CVuLyS+qSd2Dk9bbOZo/Te1G08ydIF4\nFkEWfBoixQKBgDRa1UXR7BKFCyedSOe5qN8oMGsBtjA+60MWx/pvlIW6I/wu81xG\nwrxK0EF2B6eP2O707d8oJscvCR5PJxpFWpgZCTu8IYWVrh7DIZVjJjinsZzj+lmy\nGodRbW3q06O180ulGyHAH9eBFqelco7+w8m1D+RmX2Nm74A1v45Ue5YBAoGASkxq\nLWg5AGPx1MGFLPGR+9HRK22CgMcAITPIAqHELqE6ip3gOozTR56lomfzEpV4/qKm\nC31oKIO6N60RA6JjxtBR61JChZcLnKUFoQxNq1quYxVM9vkhAylGYNWRBbYZvqrS\n5WjWn1OHzGz7oDHbrJoKV7oa/uJPyJu/Xz18Vt0CgYAwQvqEBKzCrGBIhDvoHxKT\n76cjaNT/NIK0dnexWF4Bch7RtjNqYrgol19zq5k7w7ltgOCJB3eQkx9y921D2Rfs\nTn410JX0quOX0DnArEj4JkBE3Zs8tHmOeqKnWuGQDHto8e5VyIdhpqP++Q2pP8oW\nIcUpYZFiIkGm+r3PkdtCeA==\n-----END PRIVATE KEY-----\n";
$clientEmail = 'firebase-adminsdk-44z13@push-notification-b443f.iam.gserviceaccount.com';

$payload = [
    'iss' => $clientEmail, 
    'aud' => 'https://fcm.googleapis.com/', 
    'iat' => time(), 
    'exp' => time() + 3600
];

$jwt = JWT::encode($payload, $privateKey, 'RS256');

echo 'Generated JWT Token: ' . $jwt . PHP_EOL;
