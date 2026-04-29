import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:intl/intl.dart';
import '../../models/gasto.dart';
import '../../providers/gastos_provider.dart';
import '../../theme/app_theme.dart';
import 'nuevo_gasto_screen.dart';

class GastosScreen extends StatefulWidget {
  const GastosScreen({super.key});

  @override
  State<GastosScreen> createState() => _GastosScreenState();
}

class _GastosScreenState extends State<GastosScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<GastosProvider>().cargar();
    });
  }

  @override
  Widget build(BuildContext context) {
    final prov = context.watch<GastosProvider>();
    final fmt = NumberFormat('#,##0', 'es');

    return Scaffold(
      backgroundColor: AppColors.bg,
      appBar: AppBar(
        title: const Text('Gastos'),
        actions: [
          _NavMes(
            label: prov.mesNombre,
            anio: prov.anio,
            onAnterior: prov.mesAnterior,
            onSiguiente: prov.mesSiguiente,
          ),
          const SizedBox(width: 8),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        backgroundColor: AppColors.blue,
        child: const Icon(Icons.add, color: Colors.white),
        onPressed: () => Navigator.push(context,
            MaterialPageRoute(builder: (_) => const NuevoGastoScreen())),
      ),
      body: prov.loading
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: prov.cargar,
              child: ListView(
                padding: const EdgeInsets.all(16),
                children: [
                  _TotalCard(total: prov.totalMes, fmt: fmt),
                  const SizedBox(height: 16),
                  if (prov.porCategoria.isNotEmpty) ...[
                    _GraficoCategoria(data: prov.porCategoria, fmt: fmt),
                    const SizedBox(height: 16),
                  ],
                  if (prov.gastos.isEmpty)
                    _EmptyState()
                  else
                    _ListaGastos(gastos: prov.gastos, fmt: fmt, prov: prov),
                ],
              ),
            ),
    );
  }
}

class _NavMes extends StatelessWidget {
  final String label;
  final int anio;
  final VoidCallback onAnterior;
  final VoidCallback onSiguiente;

  const _NavMes({
    required this.label,
    required this.anio,
    required this.onAnterior,
    required this.onSiguiente,
  });

  @override
  Widget build(BuildContext context) {
    final hoy = DateTime.now();
    final prov = context.watch<GastosProvider>();
    final esHoy = prov.mes == hoy.month && prov.anio == hoy.year;

    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        IconButton(
          icon: const Icon(Icons.chevron_left),
          onPressed: onAnterior,
          padding: EdgeInsets.zero,
          constraints: const BoxConstraints(),
        ),
        Text('$label $anio',
            style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w600)),
        IconButton(
          icon: Icon(Icons.chevron_right,
              color: esHoy ? AppColors.gray3 : null),
          onPressed: esHoy ? null : onSiguiente,
          padding: EdgeInsets.zero,
          constraints: const BoxConstraints(),
        ),
      ],
    );
  }
}

class _TotalCard extends StatelessWidget {
  final double total;
  final NumberFormat fmt;

  const _TotalCard({required this.total, required this.fmt});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF007AFF), Color(0xFF5856D6)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: AppColors.blue.withOpacity(0.3),
            blurRadius: 12,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('Total del mes',
              style: TextStyle(color: Colors.white70, fontSize: 13)),
          const SizedBox(height: 6),
          Text('\$${fmt.format(total)}',
              style: const TextStyle(
                  color: Colors.white,
                  fontSize: 32,
                  fontWeight: FontWeight.w700)),
        ],
      ),
    );
  }
}

class _GraficoCategoria extends StatefulWidget {
  final Map<String, double> data;
  final NumberFormat fmt;

  const _GraficoCategoria({required this.data, required this.fmt});

  @override
  State<_GraficoCategoria> createState() => _GraficoCategoriaState();
}

class _GraficoCategoriaState extends State<_GraficoCategoria> {
  int? _touched;

  static const _palette = [
    Color(0xFF007AFF), Color(0xFF34C759), Color(0xFFFF9500),
    Color(0xFFFF3B30), Color(0xFF5856D6), Color(0xFFAF52DE),
    Color(0xFF00C7BE), Color(0xFFFF2D55),
  ];

  @override
  Widget build(BuildContext context) {
    final entries = widget.data.entries.toList();
    final total = entries.fold(0.0, (s, e) => s + e.value);

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppColors.card,
        borderRadius: BorderRadius.circular(16),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text('Por categoría',
              style: TextStyle(fontSize: 13, fontWeight: FontWeight.w700,
                  color: AppColors.label)),
          const SizedBox(height: 16),
          Row(
            children: [
              SizedBox(
                width: 130,
                height: 130,
                child: PieChart(
                  PieChartData(
                    sectionsSpace: 2,
                    centerSpaceRadius: 32,
                    pieTouchData: PieTouchData(
                      touchCallback: (_, res) => setState(() {
                        _touched = res?.touchedSection?.touchedSectionIndex;
                      }),
                    ),
                    sections: List.generate(entries.length, (i) {
                      final pct = total > 0 ? entries[i].value / total : 0.0;
                      final isTouched = _touched == i;
                      return PieChartSectionData(
                        color: _palette[i % _palette.length],
                        value: entries[i].value,
                        title: '${(pct * 100).toStringAsFixed(0)}%',
                        radius: isTouched ? 50 : 42,
                        titleStyle: const TextStyle(
                            fontSize: 10, color: Colors.white,
                            fontWeight: FontWeight.w700),
                      );
                    }),
                  ),
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: List.generate(entries.length, (i) {
                    return Padding(
                      padding: const EdgeInsets.only(bottom: 6),
                      child: Row(
                        children: [
                          Container(
                            width: 10, height: 10,
                            decoration: BoxDecoration(
                              color: _palette[i % _palette.length],
                              shape: BoxShape.circle,
                            ),
                          ),
                          const SizedBox(width: 6),
                          Expanded(
                            child: Text(entries[i].key,
                                style: const TextStyle(fontSize: 11,
                                    color: AppColors.label),
                                overflow: TextOverflow.ellipsis),
                          ),
                          Text('\$${widget.fmt.format(entries[i].value)}',
                              style: const TextStyle(fontSize: 11,
                                  color: AppColors.gray,
                                  fontWeight: FontWeight.w600)),
                        ],
                      ),
                    );
                  }),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _ListaGastos extends StatelessWidget {
  final List<Gasto> gastos;
  final NumberFormat fmt;
  final GastosProvider prov;

  const _ListaGastos({
    required this.gastos,
    required this.fmt,
    required this.prov,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: AppColors.card,
        borderRadius: BorderRadius.circular(16),
      ),
      child: Column(
        children: List.generate(gastos.length, (i) {
          final g = gastos[i];
          final isLast = i == gastos.length - 1;
          return Column(
            children: [
              _GastoTile(
                gasto: g,
                fmt: fmt,
                onEdit: () => Navigator.push(context,
                    MaterialPageRoute(
                        builder: (_) => NuevoGastoScreen(gasto: g))),
                onDelete: () => _confirmDelete(context, prov, g),
              ),
              if (!isLast)
                const Divider(height: 1, indent: 56, color: AppColors.gray3),
            ],
          );
        }),
      ),
    );
  }

  void _confirmDelete(BuildContext ctx, GastosProvider prov, Gasto g) {
    showDialog(
      context: ctx,
      builder: (_) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
        title: const Text('Eliminar gasto'),
        content: Text('¿Eliminar "${g.descripcion}"?'),
        actions: [
          TextButton(
              onPressed: () => Navigator.pop(ctx),
              child: const Text('Cancelar')),
          TextButton(
            onPressed: () async {
              Navigator.pop(ctx);
              await prov.eliminarGasto(g.id!);
            },
            child: const Text('Eliminar',
                style: TextStyle(color: AppColors.red)),
          ),
        ],
      ),
    );
  }
}

class _GastoTile extends StatelessWidget {
  final Gasto gasto;
  final NumberFormat fmt;
  final VoidCallback onEdit;
  final VoidCallback onDelete;

  const _GastoTile({
    required this.gasto,
    required this.fmt,
    required this.onEdit,
    required this.onDelete,
  });

  static const _palette = [
    Color(0xFF007AFF), Color(0xFF34C759), Color(0xFFFF9500),
    Color(0xFFFF3B30), Color(0xFF5856D6), Color(0xFFAF52DE),
    Color(0xFF00C7BE), Color(0xFFFF2D55),
  ];

  Color _colorForCategory(String? cat) {
    if (cat == null) return AppColors.gray;
    return _palette[cat.hashCode.abs() % _palette.length];
  }

  @override
  Widget build(BuildContext context) {
    final dateFmt = DateFormat('dd MMM', 'es');
    final color = _colorForCategory(gasto.categoria);

    return InkWell(
      borderRadius: BorderRadius.circular(16),
      onTap: onEdit,
      onLongPress: onDelete,
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
        child: Row(
          children: [
            Container(
              width: 36, height: 36,
              decoration: BoxDecoration(
                color: color.withOpacity(0.15),
                borderRadius: BorderRadius.circular(10),
              ),
              child: Icon(Icons.receipt_outlined, color: color, size: 18),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(gasto.descripcion,
                      style: const TextStyle(
                          fontSize: 14, fontWeight: FontWeight.w600,
                          color: AppColors.label),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis),
                  if (gasto.categoria != null)
                    Text(gasto.categoria!,
                        style: TextStyle(fontSize: 11, color: color,
                            fontWeight: FontWeight.w600)),
                ],
              ),
            ),
            Column(
              crossAxisAlignment: CrossAxisAlignment.end,
              children: [
                Text('\$${fmt.format(gasto.monto)}',
                    style: const TextStyle(
                        fontSize: 15, fontWeight: FontWeight.w700,
                        color: AppColors.label)),
                Text(dateFmt.format(gasto.fecha),
                    style: const TextStyle(
                        fontSize: 11, color: AppColors.gray)),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class _EmptyState extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 40),
        child: Column(
          children: [
            Icon(Icons.receipt_long_outlined,
                size: 56, color: AppColors.gray3),
            const SizedBox(height: 12),
            const Text('Sin gastos este mes',
                style: TextStyle(color: AppColors.gray, fontSize: 15)),
            const SizedBox(height: 6),
            const Text('Toca + para agregar uno',
                style: TextStyle(color: AppColors.gray2, fontSize: 13)),
          ],
        ),
      ),
    );
  }
}
