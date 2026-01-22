/**
 * Calculate shipping cost based on district and area.
 * Dhaka Sadar: 60 tk
 * Other Dhaka areas: 120 tk
 * Outside Dhaka: 150 tk
 */

export function getShippingCost(district: string, area: string): number {
  if (district === 'Dhaka') {
    // Dhaka Sadar gets 60 tk, other areas in Dhaka get 120 tk
    return area === 'Dhaka Sadar' ? 60 : 120;
  }
  // Outside Dhaka
  return 150;
}

/**
 * Determine shipping method ID based on district and area.
 */
export function getShippingMethodId(district: string, area: string): 'inside_dhaka' | 'outside_dhaka' {
  return district === 'Dhaka' ? 'inside_dhaka' : 'outside_dhaka';
}
