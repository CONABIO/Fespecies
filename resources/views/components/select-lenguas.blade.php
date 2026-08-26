@props(['model' => 'tempNombre.lengua'])

<select
    x-model="{{ $model }}"
    {{ $attributes->merge(['class' => 'w-full px-4 py-2 mt-1 rounded-full border-2 border-gray-100 bg-gray-50 text-xs focus:border-indigo-300 outline-none transition-all']) }}
>
    <option value="">Seleccione una lengua...</option>
     <option value="Otro" class="text-[11px] font-black text-red-500 tracking-widest">Ingresar la lengua</option>
    <optgroup label="FAMILIAS LINGÜÍSTICAS">
        <option value="I Familia Álgica">I Familia Álgica</option>
        <option value="II Familia Yuto-nahua">II Familia Yuto-nahua</option>
        <option value="III Familia Cochimí-yumana">III Familia Cochimí-yumana</option>
        <option value="IV Familia Seri">IV Familia Seri</option>
        <option value="V Familia Oto-mangue">V Familia Oto-mangue</option>
        <option value="VI Familia Maya">VI Familia Maya</option>
        <option value="VII Familia Totonaco-tepehua">VII Familia Totonaco-tepehua</option>
        <option value="VIII Familia Tarasca">VIII Familia Tarasca</option>
        <option value="IX Familia Mixe-zoque">IX Familia Mixe-zoque</option>
        <option value="X Familia Chontal de Oaxaca">X Familia Chontal de Oaxaca</option>
        <option value="XI Familia Huave">XI Familia Huave</option>
    </optgroup>

    <optgroup label="LENGUAS INDIVIDUALES">
        @php
            $lenguas = [
                "Akateko", "Amuzgo", "Awakateko", "Ayapaneco", "Cora", "Cucapá", "Cuicateco",
                "Chatino", "Chichimeco jonaz", "Chinanteco", "Chocholteco", "Chontal de Oaxaca",
                "Chontal de Tabasco", "Chuj", "Ch’ol", "Guarijío", "Huasteco", "Huave",
                "Huichol", "Ixcateco", "Ixil", "Jakalteko", "Kaqchikel", "Kickapoo", "Kiliwa",
                "Kumiai", "Ku’ahl", "K’iche’", "Lacandón", "Mam", "Matlatzinca", "Maya",
                "Mayo", "Mazahua", "Mazateco", "Mixe", "Mixteco", "Náhuatl", "Oluteco",
                "Otomí", "Paipai", "Pame", "Pápago", "Pima", "Popoloca", "Popoluca de la Sierra",
                "Qato’k", "Q’anjob’al", "Q’eqchí’", "Sayulteco", "Seri", "Tarahumara",
                "Tarasco", "Teko", "Tepehua", "Tepehuano del norte", "Tepehuano del sur",
                "Texistepequeño", "Tojolabal", "Totonaco", "Triqui", "Tlahuica", "Tlapaneco",
                "Tseltal", "Tsotsil", "Yaqui", "Zapoteco", "Zoque"
            ];
        @endphp
        @foreach($lenguas as $lengua)
            <option value="{{ $lengua }}">{{ $lengua }}</option>
        @endforeach
    </optgroup>
</select>
