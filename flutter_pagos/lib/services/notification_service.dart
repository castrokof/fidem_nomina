import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:timezone/timezone.dart' as tz;
import 'package:timezone/data/latest.dart' as tz;
import '../models/factura.dart';

class NotificationService {
  static final _plugin = FlutterLocalNotificationsPlugin();
  static bool _initialized = false;

  static Future<void> init() async {
    if (_initialized) return;
    tz.initializeTimeZones();

    const android = AndroidInitializationSettings('@mipmap/ic_launcher');
    const ios = DarwinInitializationSettings(
      requestAlertPermission: true,
      requestBadgePermission: true,
      requestSoundPermission: true,
    );
    await _plugin.initialize(
      const InitializationSettings(android: android, iOS: ios),
    );
    _initialized = true;
  }

  static Future<void> programarRecordatorio(Factura factura, int mes, int anio) async {
    await init();
    final diasEnMes = DateTime(anio, mes + 1, 0).day;
    final dia = factura.diaVencimiento.clamp(1, diasEnMes);
    final vencimiento = DateTime(anio, mes, dia);
    final aviso = vencimiento.subtract(Duration(days: factura.diasAviso));

    if (aviso.isBefore(DateTime.now())) return;

    await _plugin.zonedSchedule(
      factura.id! * 100 + mes,
      '🔔 Pago próximo a vencer',
      '${factura.nombre} vence el ${dia.toString().padLeft(2, '0')}/${mes.toString().padLeft(2, '0')}/$anio',
      tz.TZDateTime.from(aviso, tz.local),
      const NotificationDetails(
        android: AndroidNotificationDetails(
          'pagos_channel', 'Recordatorios de pago',
          channelDescription: 'Notificaciones de pagos próximos',
          importance: Importance.high,
          priority: Priority.high,
          icon: '@mipmap/ic_launcher',
        ),
        iOS: DarwinNotificationDetails(
          presentAlert: true, presentBadge: true, presentSound: true,
        ),
      ),
      androidScheduleMode: AndroidScheduleMode.exactAllowWhileIdle,
      uiLocalNotificationDateInterpretation:
          UILocalNotificationDateInterpretation.absoluteTime,
    );
  }

  static Future<void> cancelarRecordatorio(int facturaId, int mes) async {
    await _plugin.cancel(facturaId * 100 + mes);
  }

  static Future<void> mostrarInmediata(String titulo, String cuerpo) async {
    await init();
    await _plugin.show(
      0, titulo, cuerpo,
      const NotificationDetails(
        android: AndroidNotificationDetails(
          'pagos_channel', 'Recordatorios de pago',
          importance: Importance.high, priority: Priority.high,
        ),
        iOS: DarwinNotificationDetails(presentAlert: true),
      ),
    );
  }
}
