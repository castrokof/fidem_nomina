import 'package:sqflite/sqflite.dart';
import 'package:path/path.dart';
import '../models/factura.dart';
import '../models/registro_pago.dart';
import '../models/gasto.dart';

class DatabaseService {
  static Database? _db;

  static Future<Database> get db async {
    _db ??= await _initDb();
    return _db!;
  }

  static Future<Database> _initDb() async {
    final path = join(await getDatabasesPath(), 'pagos_gastos.db');
    return openDatabase(path, version: 1, onCreate: _onCreate);
  }

  static Future<void> _onCreate(Database db, int version) async {
    await db.execute('''
      CREATE TABLE facturas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL,
        categoria TEXT,
        descripcion TEXT,
        referencia TEXT,
        sede TEXT,
        dia_vencimiento INTEGER NOT NULL,
        monto_estimado REAL DEFAULT 0,
        correos TEXT,
        dias_aviso INTEGER DEFAULT 3,
        activo INTEGER DEFAULT 1
      )
    ''');

    await db.execute('''
      CREATE TABLE registros_pago (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        factura_id INTEGER NOT NULL,
        mes INTEGER NOT NULL,
        anio INTEGER NOT NULL,
        estado TEXT DEFAULT 'pendiente',
        fecha_pago TEXT,
        monto_pagado REAL,
        notas TEXT,
        UNIQUE(factura_id, mes, anio),
        FOREIGN KEY(factura_id) REFERENCES facturas(id) ON DELETE CASCADE
      )
    ''');

    await db.execute('''
      CREATE TABLE categorias (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL UNIQUE,
        tipo TEXT DEFAULT 'factura'
      )
    ''');

    await db.execute('''
      CREATE TABLE gastos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        descripcion TEXT NOT NULL,
        categoria TEXT,
        monto REAL NOT NULL,
        fecha TEXT NOT NULL,
        notas TEXT,
        sede TEXT
      )
    ''');
  }

  // ── Facturas ──────────────────────────────────────────────
  static Future<int> insertFactura(Factura f) async =>
      (await db).insert('facturas', f.toMap());

  static Future<void> updateFactura(Factura f) async =>
      (await db).update('facturas', f.toMap(), where: 'id=?', whereArgs: [f.id]);

  static Future<void> deleteFactura(int id) async =>
      (await db).update('facturas', {'activo': 0}, where: 'id=?', whereArgs: [id]);

  static Future<List<Factura>> getFacturas() async {
    final rows = await (await db).query(
      'facturas', where: 'activo=1', orderBy: 'categoria, nombre',
    );
    return rows.map(Factura.fromMap).toList();
  }

  // ── Registros ─────────────────────────────────────────────
  static Future<RegistroPago> getOrCreateRegistro(int facturaId, int mes, int anio) async {
    final d = await db;
    final rows = await d.query('registros_pago',
        where: 'factura_id=? AND mes=? AND anio=?',
        whereArgs: [facturaId, mes, anio]);
    if (rows.isNotEmpty) return RegistroPago.fromMap(rows.first);
    final id = await d.insert('registros_pago', {
      'factura_id': facturaId, 'mes': mes, 'anio': anio, 'estado': 'pendiente',
    });
    return RegistroPago(id: id, facturaId: facturaId, mes: mes, anio: anio);
  }

  static Future<Map<int, Map<int, RegistroPago>>> getRegistrosPorAnio(int anio) async {
    final rows = await (await db).query(
      'registros_pago', where: 'anio=?', whereArgs: [anio],
    );
    final Map<int, Map<int, RegistroPago>> result = {};
    for (final r in rows) {
      final reg = RegistroPago.fromMap(r);
      result.putIfAbsent(reg.facturaId, () => {})[reg.mes] = reg;
    }
    return result;
  }

  static Future<void> updateRegistro(RegistroPago r) async =>
      (await db).update('registros_pago', r.toMap(), where: 'id=?', whereArgs: [r.id]);

  // ── Categorías ────────────────────────────────────────────
  static Future<List<String>> getCategorias({String tipo = 'factura'}) async {
    final rows = await (await db).query(
      'categorias', where: 'tipo=?', whereArgs: [tipo], orderBy: 'nombre',
    );
    return rows.map((r) => r['nombre'] as String).toList();
  }

  static Future<void> insertCategoria(String nombre, {String tipo = 'factura'}) async {
    final d = await db;
    await d.insert('categorias', {'nombre': nombre, 'tipo': tipo},
        conflictAlgorithm: ConflictAlgorithm.ignore);
  }

  // ── Gastos ────────────────────────────────────────────────
  static Future<int> insertGasto(Gasto g) async =>
      (await db).insert('gastos', g.toMap());

  static Future<void> updateGasto(Gasto g) async =>
      (await db).update('gastos', g.toMap(), where: 'id=?', whereArgs: [g.id]);

  static Future<void> deleteGasto(int id) async =>
      (await db).delete('gastos', where: 'id=?', whereArgs: [id]);

  static Future<List<Gasto>> getGastos({int? mes, int? anio}) async {
    String? where;
    List<dynamic> args = [];
    if (mes != null && anio != null) {
      where = "strftime('%m', fecha)=? AND strftime('%Y', fecha)=?";
      args = [mes.toString().padLeft(2, '0'), anio.toString()];
    }
    final rows = await (await db).query(
      'gastos', where: where, whereArgs: args.isEmpty ? null : args,
      orderBy: 'fecha DESC',
    );
    return rows.map(Gasto.fromMap).toList();
  }

  static Future<double> getTotalGastos({int? mes, int? anio}) async {
    String sql = 'SELECT COALESCE(SUM(monto),0) as total FROM gastos';
    List<dynamic> args = [];
    if (mes != null && anio != null) {
      sql += " WHERE strftime('%m',fecha)=? AND strftime('%Y',fecha)=?";
      args = [mes.toString().padLeft(2, '0'), anio.toString()];
    }
    final result = await (await db).rawQuery(sql, args.isEmpty ? null : args);
    return (result.first['total'] as num).toDouble();
  }

  static Future<Map<String, double>> getGastosPorCategoria({int? mes, int? anio}) async {
    String sql = '''
      SELECT COALESCE(categoria,'Sin categoría') as cat, SUM(monto) as total
      FROM gastos
    ''';
    List<dynamic> args = [];
    if (mes != null && anio != null) {
      sql += " WHERE strftime('%m',fecha)=? AND strftime('%Y',fecha)=?";
      args = [mes.toString().padLeft(2, '0'), anio.toString()];
    }
    sql += ' GROUP BY cat ORDER BY total DESC';
    final rows = await (await db).rawQuery(sql, args.isEmpty ? null : args);
    return {for (final r in rows) r['cat'] as String: (r['total'] as num).toDouble()};
  }
}
