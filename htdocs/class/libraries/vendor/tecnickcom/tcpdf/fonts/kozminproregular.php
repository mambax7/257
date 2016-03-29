<?php
$type = 'cidfont0';
$name = 'KozMinPro-Regular-Acro';
$displayname = 'Kozuka Mincho Pro (Japanese Serif)';
$desc = array(
    'Ascent' => 880,
    'Descent' => -120,
    'CapHeight' => 740,
    'Flags' => 6,
    'FontBBox' => '[-195 -272 1110 1075]',
    'ItalicAngle' => 0,
    'StemV' => 86,
    'XHeight' => 502,
);
$cidinfo = array(
    'Registry' => 'Adobe',
    'Ordering' => 'Japan1',
    'Supplement' => '4',
);
$enc = 'UniJIS-UCS2-H';

$up = -75;
$ut = 50;

$dw = 1000;
$cw = array(
    32 => 278, 33 => 299, 34 => 353, 35 => 614, 36 => 614, 37 => 721, 38 => 735, 39 => 216, 40 => 323, 41 => 323,
    42 => 449, 43 => 529, 44 => 219, 45 => 306, 46 => 219, 47 => 453, 48 => 614, 49 => 614, 50 => 614, 51 => 614,
    52 => 614, 53 => 614, 54 => 614, 55 => 614, 56 => 614, 57 => 614, 58 => 219, 59 => 219, 60 => 529, 61 => 529,
    62 => 529, 63 => 486, 64 => 744, 65 => 646, 66 => 604, 67 => 617, 68 => 681, 69 => 567, 70 => 537, 71 => 647,
    72 => 738, 73 => 320, 74 => 433, 75 => 637, 76 => 566, 77 => 904, 78 => 710, 79 => 716, 80 => 605, 81 => 716,
    82 => 623, 83 => 517, 84 => 601, 85 => 690, 86 => 668, 87 => 990, 88 => 681, 89 => 634, 90 => 578, 91 => 316,
    92 => 614, 93 => 316, 94 => 529, 95 => 500, 96 => 387, 97 => 509, 98 => 566, 99 => 478, 100 => 565, 101 => 503,
    102 => 337, 103 => 549, 104 => 580, 105 => 275, 106 => 266, 107 => 544, 108 => 276, 109 => 854, 110 => 579, 111 => 550,
    112 => 578, 113 => 566, 114 => 410, 115 => 444, 116 => 340, 117 => 575, 118 => 512, 119 => 760, 120 => 503, 121 => 529,
    122 => 453, 123 => 326, 124 => 380, 125 => 326, 126 => 387, 127 => 216, 128 => 453, 129 => 216, 130 => 380, 131 => 529,
    132 => 299, 133 => 614, 134 => 614, 135 => 265, 136 => 614, 137 => 475, 138 => 614, 139 => 353, 140 => 451, 141 => 291,
    142 => 291, 143 => 588, 144 => 589, 145 => 500, 146 => 476, 147 => 476, 148 => 219, 149 => 494, 150 => 452, 151 => 216,
    152 => 353, 153 => 353, 154 => 451, 156 => 1075, 157 => 486, 158 => 387, 159 => 387, 160 => 387, 161 => 387,
    162 => 387, 163 => 387, 164 => 387, 165 => 387, 166 => 387, 167 => 387, 168 => 387, 170 => 880, 171 => 448,
    172 => 566, 173 => 716, 174 => 903, 175 => 460, 176 => 805, 177 => 275, 178 => 276, 179 => 550, 180 => 886, 181 => 582,
    182 => 529, 183 => 738, 184 => 529, 185 => 738, 186 => 357, 187 => 529, 188 => 406, 189 => 406, 190 => 575, 191 => 406,
    192 => 934, 193 => 934, 194 => 934, 195 => 646, 196 => 646, 197 => 646, 198 => 646, 199 => 646, 200 => 646, 201 => 617,
    202 => 567, 203 => 567, 204 => 567, 205 => 567, 206 => 320, 207 => 320, 208 => 320, 209 => 320, 210 => 681, 211 => 710,
    212 => 716, 213 => 716, 214 => 716, 215 => 716, 216 => 716, 217 => 529, 218 => 690, 219 => 690, 220 => 690, 221 => 690,
    222 => 634, 223 => 605, 224 => 509, 225 => 509, 226 => 509, 227 => 509, 228 => 509, 229 => 509, 230 => 478, 231 => 503,
    232 => 503, 233 => 503, 234 => 503, 235 => 275, 236 => 275, 237 => 275, 238 => 275, 239 => 550, 240 => 579, 241 => 550,
    242 => 550, 243 => 550, 244 => 550, 245 => 550, 246 => 529, 247 => 575, 248 => 575, 249 => 575, 250 => 575, 251 => 529,
    252 => 578, 253 => 529, 254 => 517, 255 => 634, 256 => 578, 257 => 445, 258 => 444, 259 => 842, 260 => 453, 261 => 614,
);
$_cr = array(
    array(231, 632, 500), // half-width
    array(8718, 8718, 500),
    array(9738, 9757, 250), // quarter-width
    array(9758, 9778, 333), // third-width
    array(12063, 12087, 500),
);
foreach($_cr as $_r) {
    for($i = $_r[0]; $i <= $_r[1]; $i++) {
        $cw[$i+31] = $_r[2];
    }
}
// --- EOF ---
