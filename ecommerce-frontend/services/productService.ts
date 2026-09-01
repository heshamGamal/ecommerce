import api from './api';
import { Product } from '@/types';

export async function getProducts(): Promise<Product[]> {
  try {
      const response = await api.get('/products');
          return response.data.data || response.data || [];
            } catch (error) {
                console.error('Error fetching products:', error);
                    return [];
                      }
                      }

                      export async function getProductBySlug(slug: string): Promise<Product | null> {
                        try {
                            const response = await api.get(`/products/${slug}`);
                                return response.data.product || response.data || null;
                                  } catch (error) {
                                      console.error(`Error fetching product ${slug}:`, error);
                                          return null;
                                            }
                                            }
                                            