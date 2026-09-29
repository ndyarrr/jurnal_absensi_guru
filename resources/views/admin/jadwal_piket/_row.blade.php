<div class="pk-teacher-row">
    <div style="min-width: 0;">
        <div style="font-weight: 800; font-size: 0.85rem; color: #1e2538;">{{ optional($item->guru)->nama_guru ?? 'Guru Piket' }}</div>
        <div style="font-size: 0.72rem; color: #64748b;">
            NIP: {{ optional($item->guru)->nip ?? '-' }}
            @unless($item->tanggal)
                &middot; <span style="color: #b45309; font-weight: 700;">mingguan (berulang)</span>
            @endunless
        </div>
    </div>

    <div style="display: flex; gap: 6px; align-items: center;">
        <button type="button" class="pk-edit-btn"
            data-id="{{ $item->id_piket }}"
            data-label="{{ $item->tanggal ? $item->tanggal->translatedFormat('l, d F Y') : 'Setiap ' . $item->hari . ' (mingguan berulang)' }}"
            data-peran="{{ $item->peran }}"
            data-guru-id="{{ $item->id_guru }}"
            data-guru-label="{{ optional($item->guru)->nama_guru }} (NIP: {{ optional($item->guru)->nip ?? '-' }})"
            data-keterangan="{{ $item->keterangan && $item->keterangan !== $item->peran ? $item->keterangan : '' }}"
            onclick="openEditPiketModal(this)"
            style="background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; padding: 6px 10px; border-radius: 8px; cursor: pointer; font-size: 0.8rem;" title="Edit Tugas">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
        <form action="{{ route('jadwal-piket.destroy', $item->id_piket) }}" method="POST" data-confirm-type="delete" data-confirm="Apakah Anda yakin ingin menghapus penugasan piket ini?">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 6px 10px; border-radius: 8px; cursor: pointer; font-size: 0.8rem;" title="Hapus Tugas">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </form>
    </div>
</div>