export type ApiResponse<T, E = {[x: string]: string}> = {
  success: boolean;
  error?: E;
  result: T;
};
