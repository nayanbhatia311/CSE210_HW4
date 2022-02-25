
load harness
@test "custom-1" {
  check 'x := 1 * 9 ; if 5 < x then x := 2 - 2 else y := 9' '⇒ skip; if (5<x) then { x := (2-2) } else { y := 9 }, {x → 9}
⇒ if (5<x) then { x := (2-2) } else { y := 9 }, {x → 9}
⇒ x := (2-2), {x → 9}
⇒ skip, {x → 0}'
}
@test "custom-2" {
  check 'while false do x := 1 ; y := 1' '⇒ skip; y := 1, {}
⇒ y := 1, {}
⇒ skip, {y → 1}'
}
@test "custom-3" {
  check 'if ( true ∧ true ) then p := t else p := t + 1' '⇒ p := t, {}
⇒ skip, {p → 0}'
}
@test "custom-4" {
  check 'if ( ¬ true ) then y := z + 3 else wz := -1 + x' '⇒ wz := (-1+x), {}
⇒ skip, {wz → -1}'
}
@test "custom-5" {
  check 'if ( le * z < x - p ∧ 3 - 2 < 4 * x ) then y := z else y := z - x' '⇒ y := (z-x), {}
⇒ skip, {y → 0}'
}
